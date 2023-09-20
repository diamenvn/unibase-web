<?php

namespace App\Services;

use App\Model\Mongo\CompanyProductModel;
use App\Model\Mongo\ListProductModel;
use App\Model\Mongo\SettingApiImportModel;
use App\Model\Mongo\SettingConnectModel;
use App\Model\Mongo\SettingGroupModel;
use Exception;

class SettingService
{

    public function __construct(SettingGroupModel $group, SettingConnectModel $connect, SettingApiImportModel $import, CompanyProductModel $product, ListProductModel $listProduct)
    {
        $this->group = $group;
        $this->connect = $connect;
        $this->import = $import;
        $this->product = $product;
        $this->listProduct = $listProduct;
    }

    public function search($data)
    {
        $query = $this->result;
        if (isset($data['email']) && $data['email'] != '') {
            $query = $query->where('email', $data['email']);
        }
        if (isset($data['phone']) && $data['phone'] != '') {
            $query = $query->where('phone', $data['phone']);
        }
        if (isset($data['name']) && $data['name'] != '') {
            $query = $query->where('name', 'like', '%' . $data['name'] . '%');
        }
        if (isset($data['source_id']) && $data['source_id'] != '') {
            $query = $query->where('source_id', $data['source_id']);
        }
        if (isset($data['status']) && $data['status'] > -1) {
            if(is_array($data['status'])){
                $data['status'] = array_map(
                    function($value) { return (int)$value; },
                    $data['status']
                );
                $query = $query->whereIn('status', $data['status']);
            }else{
                $query = $query->where('status', (int)$data['status']);
            }
        }
        if (isset($data['sortBy']) && $data['sortBy'] != '') {
            $query = $query->orderBy($data['sortBy'], isset($data['sortOrder']) ? $data['sortOrder'] : 'DESC');
        } else {
            $query = $query->orderBy('id', 'DESC');
        }
        return $query;
    }

    public function query($data)
    {
        $query = $this->result;
        foreach ($data as $key => $value) {
            if(is_array($value)){
                $query = $query->whereIn($key, $value);
            }else{
                $query = $query->where($key, $value);
            }
        }
        $data = $query;
        return $data;
    }

    public function update($target, $replace)
    {
        try {
            foreach ($replace as $key => $value) {
                $target->$key = $value;
            }
            $target->save();
            return $target;
        } catch (Exception  $e) {
            throw $e;
        }
    }

    public function createGroup($data)
    {
        try {
            $table = new $this->group;
            foreach ($data as $key => $value) {
                $table->$key = $value;
            }
            $table->save();
            return $table;
        } catch (Exception  $e) {
            throw $e;
        }
    }

    public function getGroupByCompanyId($companyId)
    {
        $query = $this->group;
        $query = $query->where('company_id', $companyId);
        $query = $query->where('status', 1);
        $query = $query->orderBy('_id', 'DESC');
        return $query->get();
    }

    public function findGroupByCustomerId($customerId)
    {
        $query = $this->group;
        $query = $query->where('leaders', $customerId);
        $query = $query->where('status', 1);
        return $query->get();
    }

    public function firstGroup($id)
    {
        return $this->group->find($id);
    }


    /// Connect Company

    public function getCompanyConnectFromMKT($id)
    {
        $query = $this->connect;
        $query = $query->where('company_mkt_id', $id);
        $query = $query->where('status', 1);
        
        return $query->groupBy('company_sale_id')->get();
    }

    public function getCompanyConnectFromSale($id)
    {
        $query = $this->connect;
        $query = $query->where('company_sale_id', $id);
        $query = $query->where('status', 1);
        return $query->groupBy('company_mkt_id')->get();
    }

    public function addConnectCompany($data)
    {
        $query = new $this->connect;
        foreach ($data as $key => $value) {
            $query->$key = $value;
        }
        $query->status = 1;
        if ($query->save()){
            return $query;
        }else{
            return false;
        }
    }

    /// IMPORT DATA API

    public function createImportAPI($data)
    {
        try {
            $table = new $this->import;
            foreach ($data as $key => $value) {
                $table->$key = $value;
            }
            $table->save();
            return $table;
        } catch (Exception  $e) {
            throw $e;
        }
    }
    public function updateImportAPI($target, $replace)
    {
        try {
            foreach ($replace as $key => $value) {
                $target->$key = $value;
            }
            $target->save();
            return $target;
        } catch (Exception  $e) {
            throw $e;
        }
    }
    
    public function removeImportAPI($id)
    {
        $query = $this->import;
        $query = $query->where('_id', $id)->delete();
        return $query;
    }

    public function getByCustomerId($customerId)
    {
        $query = $this->import;
        $query = $query->where('user_create_id', $customerId);
        $query = $query->orderBy('_id', 'DESC');
        return $query->paginate(10)->setPath('');
    }

    public function firstImport($id)
    {
        return $this->import->find($id);
    }

    public function firstImportByCustomerId($id, $customerId)
    {
        $query = $this->import;
        $query = $query->where('_id', $id);
        $query = $query->where('user_create_id', $customerId);
        return $query->first();
    }


    //==== PRODUCT  =====//

    public function getListProductByCompanyId($id)
    {
        $query = $this->product;
        $query = $query->where('company_id', $id);
        $query = $query->where('status', 1);
        $query = $query->orderBy('_id', 'DESC');
        return $query->get()->load('product');
    }

    public function hiddenProductById($id)
    {
        $query = $this->product;
        $query = $query->where('_id', $id);
        $query = $query->update(array('status' => 0));
        return $query;
    }

    public function removeProductById($id)
    {
        $query = $this->product;
        $query = $query->where('_id', $id);
        $query = $query->delete();
        return $query;
    }

    public function addProduct($data)
    {
        $query = new $this->listProduct;
        $query['product_name'] = $data['product_name'];
        $query['price'] = $data['price'];
        $query['status'] = 1;
        if ($query->save()){
            $product = new $this->product;
            $product['company_id'] = $data['company_mkt_id'];
            $product['product_id'] = $query->_id;
            $product['status'] = 1;
            $product->save();
            return $query;
        }else{
            return false;
        }
    }
}


?>
