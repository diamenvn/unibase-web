<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class LadingExport implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $ship;
    protected $order;

    public function __construct($ship,$order, $customer, $request)
    {
        $this->ship = $ship;
        $this->order = $order;
        $this->request = $request;
        $this->customer = $customer;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:I1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray(array(
                    'fill' => array(
                        'type'  => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FF0000')
                    )
                ));
            },
        ];
    }

    public function headings(): array
    {
        return [
            'STT',
            'Tên khách hàng',
            'Số điện thoại',
            'Địa chỉ giao hàng',
            'Đơn từ sale',
            'Sản phẩm',
            'Thành tiền',
            'Trạng thái đơn hàng',
            'Ngày nhận đơn'
        ];
    }


    public function collection()
    {
        $request = $this->request;
        $customer = $this->customer->info();
        $request = $this->acceptRequest($request);
        $request = $this->defaultRequest($request);
        $request = $this->filterByPermissionCustomer($request);
        $products = [];
        $request['user_create_id'] = null;
        $total = 0;
        $stt = 0;
        $data  = collect();
        $export = collect();

        if ($this->customer->isMkt()) {
            $companyIdSaleConnect = $customer->load('companyMkt')->companyMkt->pluck('company_sale_id')->toArray();
            $userCreateId = $this->customer->getAllByCompanyId($companyIdSaleConnect);
        } else {
            $userCreateId = $customer->load('customer')->customer;
        }
        if ($userCreateId) {
            $request['user_create_id'] = $userCreateId->pluck('_id')->toArray();
        }
        $products = $this->order->getByStatusActive()->keyBy('_id');

        $lists = $this->ship->search($request)
            ->with(['order' => function ($q) use ($request) {

                $q->where('reason', "success");
                if (!empty($request['filter_status'])) {
                    $q->whereIn('filter_status', $request['filter_status']);
                }
                if (!empty($request['filter_ship'])) {
                    $q->whereIn('filter_ship', $request['filter_ship']);
                }
                
                if (!empty($request['filter_confirm']) && !in_array("null", $this->request['filter_confirm'])) {
                    $q->whereIn('filter_confirm', $request['filter_confirm']);
                } elseif (!empty($request['filter_confirm']) &&  in_array("null", $this->request['filter_confirm'])) {
                   
                    $q->whereNull('filter_confirm');
                }
                if (!empty($request['filter_date_by']) && $request['filter_date_by'] == "date_confirm") {
                    $filterDateBy = "created_at";
                    if (!empty($request['type_confirm_text']) && $request['type_confirm_text'] == "date_confirm") {
                        $filterDateBy = "date_confirm";
                    }

                    if (!empty($request['time_start'])) {
                        $time = Carbon::parse($request['time_start'] . " " . "00:00:00");

                        $q->where($filterDateBy, '>=', $time);
                    }
                    if (!empty($request['time_end'])) {
                        $time = Carbon::parse($request['time_end'] . " " . "23:59:59");
                        $q->where($filterDateBy, '<=', $time);
                    }
                }

                $q
                    ->with('product')
                    ->with('reciver')
                    ->with('source')
                    ->with('filterConfirm')
                    ->with('activityLanding');
            }])->get();

        foreach ($lists as $value) {
            if (!empty($value->order) && $value->order->reason == "success") {
                $data->push($value);
                $total += (int) $value->total;
            }
        }
        foreach ($data as $value) {
            if (!empty($value->order)) {
                $stt += 1;
                $item = $arrProductExist = $arrListProduct = [];
                $item['STT'] = $stt;
                $item['name'] = $value->name;
                $item['phone'] = (string) $value->phone;
                $item['address'] = '';
                if (!empty($value->address)) {
                    $item['address'] .= $value->address . ' - ';
                }
                if (!empty($value->town)) {
                    $item['address'] .= $value->town . ' - ';
                }
                if (!empty($value->district)) {
                    $item['address'] .= $value->district . ' - ';
                }
                if (!empty($value->provin)) {
                    $item['address'] .= $value->provin;
                }
                $item['user_reciver'] = '';
                if (!empty($value->order->reciver)) {
                    $item['user_reciver'] = $value->order->reciver->name;
                }
                $item['product'] = '';


                foreach ($value->product as $key => $productItem) {

                    if (!in_array($productItem['product_id'], $arrProductExist)) {
                        $itemProduct = [
                            'amount' => $productItem['amount'],
                            'name' => $products[$productItem['product_id']]->product_name
                        ];
                        $arrListProduct[$productItem['product_id']] = $itemProduct;
                        array_push($arrProductExist, $productItem['product_id']);
                    } else {
                        $itemProduct = [
                            'amount' => $arrListProduct[$productItem['product_id']]->amount + $productItem['amount'],
                            'name' => $products->product[$productItem['product_id']]->product_name
                        ];
                        $arrListProduct[$productItem['product_id']] = $itemProduct;
                    }
                }

                foreach ($arrListProduct as $key => $productItem) {
                    $item['product'] .= $productItem['amount'] . ' ' . $productItem['name'] . ', ';
                }
                $item['product'] = substr($item['product'], 0, -2);

                $item['total'] = number_format($value->total);
                $item['status'] = isset($value->order->filterConfirm) ? $value->order->filterConfirm->text : "Đơn chưa xác nhận";
                $item['created_at'] = $value->created_at;

                $export->push($item);
            }
        }
        ob_end_clean();
        ob_start();
        return $export;
    }



    public function getCustomerFromArrayId($array)
    {
        if (!empty($array)) {
            $array = $this->arrayMerge($array);
            return $this->customer->get(['_id' => $array]);
        } else {
            return [];
        }
    }

    public function getMembersGroupByCustomer($customer)
    {
        $group = $this->setting->findGroupByCustomerId($customer->_id)->pluck('members');
        $group->push(array($customer->_id));
        $groups = $this->arrayMerge($group->toArray());
        return $groups;
    }

    public function arrayMerge($array)
    {
        if (!empty($array)) {
            return array_unique(array_merge(...$array));
        } else {
            return [];
        }
    }

    public function filterByPermissionCustomer($request)
    {
        $customer = $this->customer->info();
        $company = $customer->load('company')->company;
        if ($company->company_type == "mkt" || ($company->company_type == "sale" && ($company->divideOrder == 1 || empty($company->divideOrder)))) {
            $request = $this->filterByDivideOrder($customer, $request);
            $request['divideOrder'] = 1;
        } else {
            $request = $this->filterByAutoGetOrder($customer, $request);
            $request['divideOrder'] = 0;
        }

        return $request;
    }

    public function filterByDivideOrder($customer, $request)
    {
        if ($this->customer->isAdminMkt()) {
            $request['company_id'] = $customer->company_id;
        } elseif ($this->customer->isAdminSale() || $this->customer->isVandon()) {
            $connect = $customer->load('companySale')->companySale;
            $request['product_id'] = $connect->pluck('product_id')->toArray();
            $request['company_id'] = $connect->pluck('company_mkt_id')->toArray();
        } elseif ($this->customer->isMkt()) {
            $filter = $this->getMembersGroupByCustomer($customer);
            $request['user_create_id'] = $filter;
        } elseif ($this->customer->isSale()) {
            $filter = $this->getMembersGroupByCustomer($customer);
            $request['user_reciver_id'] = $filter;
        }

        if ($this->customer->isSale()) {
            if (!empty($request['user_id'])) {
                $request['user_reciver_id'] = $request['user_id'];
            }
        } else {
            if (!empty($request['user_id'])) {
                $request['user_create_id'] = $request['user_id'];
            }
        }

        return $request;
    }

    public function filterByAutoGetOrder($customer, $request)
    {
        if ($this->customer->isAdminMkt()) {
            $request['company_id'] = $customer->company_id;
        } elseif ($this->customer->isAdminSale() || $this->customer->isVandon()) {
            $connect = $customer->load('companySale')->companySale;
            $request['product_id'] = $connect->pluck('product_id')->toArray();
            $request['company_id'] = $connect->pluck('company_mkt_id')->toArray();
        } elseif ($this->customer->isMkt()) {
            $filter = $this->getMembersGroupByCustomer($customer);
            $request['user_create_id'] = $filter;
        } elseif ($this->customer->isSale()) {
            $connect = $customer->load('companySale')->companySale;
            $request['product_id'] = $connect->pluck('product_id')->toArray();
            $request['company_id'] = $connect->pluck('company_mkt_id')->toArray();
            $request['date_reciver'] = null;
            $request['user_reciver_id'] = $customer->_id;
        }

        if ($this->customer->isSale()) {
            if (!empty($request['user_id'])) {
                $request['user_reciver_id'] = $request['user_id'];
            }
        } else {
            if (!empty($request['user_id'])) {
                $request['user_create_id'] = $request['user_id'];
            }
        }

        return $request;
    }


    public function defaultRequest($request)
    {
        $request['limit'] = !empty($request['limit']) ? (int) $request['limit'] : 20;
        if ($this->customer->isVandon() && $this->customer->isUser()) {
            $request['reason'] = "success";
        }

        $request['filter_date_by'] = !empty($request['filter_date_by']) ? $request['filter_date_by'] : 'created_at';

        return $request;
    }

    public function acceptRequest($request)
    {
        return $request->only(
            'search',
            'product_id',
            'order_id',
            'company_mkt_id',
            'source_id',
            'user_create_id',
            'user_reciver_id',
            'filter_status',
            'filter_ship',
            'time_start',
            'time_end',
            'type_confirm_text',
            'filter_date_by',
            'filter_confirm',
            'limit',
            ''
        );
    }
}
