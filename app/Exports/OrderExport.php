<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class OrderExport implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $ship;
    protected $order;

    public function __construct($order, $customer, $request)
    {
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
            'Địa chỉ khách',
            'Nhân sự phụ trách',
            'Sản phẩm quan tâm',
            'Trạng thái đơn hàng',
            'Ngày tạo đơn'
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
        // $request['user_create_id'] = null;
        $total = 0;
        $stt = 0;
        $data  = collect();
        $export = collect();

        $reason = array(
            'wait' => "Chưa chốt được",
            'success'=> "Đơn đã chốt",
            'cancel'=> "Đơn huỷ",
            'null' => "Đơn hàng mới"
        );


        $lists = $this->order->search($request)
            ->select('name', 'phone', 'date_reciver', 'product_id', 'address', 'source_id', 'user_reciver_id', 'proccessing', 'reason', 'created_at', 'filter_status')
            ->with('product')
            ->with('source')
            ->with('reciver')
            ->with('filterStatus')
            ->with([
                'order' => function ($query) use ($request) {
                    if (!empty($request['company_id'])) {
                        if (is_array($request['company_id'])) {
                            $query->whereIn('company_id', $request['company_id']);
                        } else {
                            $query->where('company_id', $request['company_id']);
                        }
                    }
                },
            ])->get();

        foreach ($lists as $value) {
            // if (!empty($value->order)) {
            $stt += 1;
            $item = $arrProductExist = $arrListProduct = [];
            $item['STT'] = $stt;
            $item['name'] = $value->name;
            $item['phone'] = (string) $value->phone;
            $item['address'] = $value->address;

            $item['user_reciver'] = '';
            if (!empty($value->user_reciver_id)) {
                $item['user_reciver'] = $value->reciver->name;
            }
            $item['product'] = '';

            if (!empty($value->product_id)) {
                $item['product'] = $value->product->product_name;
            }

            try {
                $item['status'] = $reason[$value->reason];
            } catch (\Throwable $th) {
                $item['status'] = $reason['null'];
            }
            $item['created_at'] = $value->created_at;

            $export->push($item);
            // }
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
        if (!empty($request->note)) {
            $request['note'] = XSSCleaner::clean($request->note);
        }
        return $request->only(
            'search',
            'name',
            'phone',
            'address',
            'product_id',
            'order_id',
            'company_mkt_id',
            'source_id',
            'user_id',
            'user_create_id',
            'user_reciver_id',
            'link',
            'reason',
            'filter_status',
            'filter_ship',
            'filter_confirm',
            'note',
            'message',
            'ship-name',
            'ship-phone',
            'ship-provin',
            'ship-district',
            'ship-town',
            'ship-address',
            'ship-product',
            'ship-amount',
            'ship-price',
            'ship-note_ship',
            'ship-note_delivery',
            'ship-discount',
            'ship-transport',
            'ship-charge',
            'ship-total',
            'time_start',
            'time_end',
            'created_at',
            'updated_at',
            'type_confirm_text',
            'filter_date_by',
            'limit',
            ''
        );
    }
}
