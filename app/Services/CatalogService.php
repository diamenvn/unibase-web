<?php

namespace App\Services;

use App\Model\Mongo\ProductModel;
use Exception;

class CatalogService
{

    public function __construct(ProductModel $product)
    {
        $this->product = $product;
    }

    //==== PRODUCT  =====//

    public function firstById($id)
    {
        return $this->product->find($id);
    }

    public function paginateListProductByUserID($userId)
    {
        $query = $this->product;
        $query = $query->where('created_by', $userId);
        $query = $query->where('status', 1);
        $query = $query->orderBy('_id', 'DESC');
        return $query->paginate();
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

    public function createProduct($data)
    {
        $fieldCurrency = [
            'product_cost',
            'product_ship_price'
        ];

        foreach ($fieldCurrency as $key => $value) {
            $data[$value] = str_replace(",", "", $data[$value]);
        }

        $data['status'] = 1;

        try {
            $query = new $this->product;
            foreach ($data as $key => $value) {
                $query->$key = $value;
            }
            $query->save();
            return $query;
        } catch (Exception  $e) {
            throw $e;
        }
    }
}


?>
