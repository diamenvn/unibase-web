<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ListProvinService;
use Illuminate\Http\Request;

class ApiInfoController extends Controller
{
  public function __construct(ListProvinService $provin)
  {
    $this->provin = $provin;
    $this->msg = "Error!";
    $this->success = false;
    $this->data = [];
  }


  public function getProvin()
  {
    $provins = $this->provin->get();
    if (!empty($provins)){
        $this->success = true;
        $this->msg = "Lấy dữ liệu thành công!";
        $this->data = $provins;
    }
    
    return response()->json(array('success' => $this->success, 'msg' => $this->msg, 'data' => $this->data));
  }

  public function getDistrict(Request $request)
  {
    $provinId = $request->input('ID', null);

    $districts = $this->provin->getDistrictByProvinId(['provin_id' => $provinId]);

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

    $towns = $this->provin->getTownByDistrictId(['district_id' => $districtId]);
    if (!empty($towns)){
        $this->success = true;
        $this->msg = "Lấy dữ liệu thành công!";
        $this->data = $towns;
    }
    
    return response()->json(array('success' => $this->success, 'msg' => $this->msg, 'data' => $this->data));
  }
}
