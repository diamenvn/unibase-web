<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function __construct(CustomerService $customer, SettingService $setting)
    {
        $this->customer = $customer;
        $this->setting = $setting;
    }

    public function group(Request $request)
    {
        $request = $request->only('group_id');

        $customer = $this->customer->info();
        $listCustomers = $this->customer->getByCompanyId($customer->company_id);
        $listGroups = $this->setting->getGroupByCompanyId($customer->company_id);
        $members = [];
        $detailGroup = [];
        $id = 0;
        if (!empty($request['group_id'])) {
            $detailGroup = $this->setting->firstGroup($request['group_id']);
            $members = $this->customer->get(['_id' => $detailGroup->members]);
            $id = $detailGroup->_id;
            if (!empty($members)) {
                $members = $members->keyBy('_id');
            }
        }

        return view('site.setting.group')
            ->with('listGroups', $listGroups)
            ->with('members', $members)
            ->with('detailGroup', $detailGroup)
            ->with('id', $id)
            ->with('listCustomers', $listCustomers);
    }

    public function addProduct()
    {
        $customer = $this->customer->info();
        $data['product'] = $this->setting->getListProductByCompanyId($customer->company_id)->load('connect.companySale');
        $data['connect'] = $this->setting->getCompanyConnectFromMKT($customer->company_id)->load('companySale');
        $data['companyType'] = $customer->load('company')->company->company_type;

        return view('site.setting.addproduct', $data);
    }

    public function importFromAPI()
    {
        $customer = $this->customer->info()
        ->load('product.product');
    
        return view('site.setting.import_ladipage')
        ->with('customer', $customer);
    }

    public function getCustomerFromArrayId($array)
    {
        if (!empty($array)) {
            return $this->customer->get(['_id' => $array]);
        } else {
            return [];
        }
    }
}
