<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderListService;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
  public function __construct(OrderListService $order)
  {
    $this->order = $order;
    $this->msg = "Error!";
    $this->success = false;
    $this->data = [];
  }


  public function create(Request $request)
  {
    dd($request);
    
    return response()->json(array('success' => $this->success, 'msg' => $this->msg, 'data' => $this->data));
  }

  public function getDistrict(Request $request)
  {
    $provinId = $request->input('ID', null);

    $districts = $this->provin->getDistrictByProvinId(['TinhThanhID' => (int)$provinId]);
    if (!empty($districts)){
        $this->success = true;
        $this->msg = "Lấy dữ liệu thành công!";
        $this->data = $districts;
    }
    
    return response()->json(array('success' => $this->success, 'msg' => $this->msg, 'data' => $this->data));
  }

  public function getTown(Request $request)
  {
    $districtId = $request->input('ID', null);

    $towns = $this->provin->getTownByDistrictId(['QuanHuyenID' => (int)$districtId]);
    if (!empty($towns)){
        $this->success = true;
        $this->msg = "Lấy dữ liệu thành công!";
        $this->data = $towns;
    }
    
    return response()->json(array('success' => $this->success, 'msg' => $this->msg, 'data' => $this->data));
  }
}
