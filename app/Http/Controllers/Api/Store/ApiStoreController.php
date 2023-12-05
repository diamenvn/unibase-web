<?php

namespace App\Http\Controllers\Api\Store;

use App\Helpers\App;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\CustomerService;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiStoreController extends Controller
{
  public function __construct(
    CustomerService $customerService,
    ApiService $apiService
  )
  {
    $this->customerService = $customerService;
    $this->apiService = $apiService;
    $this->response['msg'] = "Error!";
    $this->response['success'] = false;
    $this->response['data'] = [];
    $this->timeNow = Carbon::now();
  }

  public function list(Request $request)
  {
    $fetch = $this->apiService->get(config('api.store.list'));
    $parse = $this->apiService->parse($fetch);
    
    if ($parse['success']) {
      $this->response['success'] = true;
      $this->response['msg'] = 'Lấy dữ liệu thành công';
      $this->response['data'] = $parse['response']->result;
    }

    return response()->json($this->response, 200);
  }
}
