<?php

namespace App\Http\Controllers\Webhook;

use App\Helpers\App;
use App\Services\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WebhookTiktokController extends Controller
{
  public function __construct(ApiService $apiService)
  {
    $this->apiService = $apiService;
  }

  public function callback(Request $request)
  {
    $request = $request->only("code");
    $code = $request["code"];
    if (empty($code)) {
        abort(401);
    } 

    $body = [
        'code' => $code
    ];
    $fetch = $this->apiService->post(config('api.store.create'), $body);
    $parse = $this->apiService->parse($fetch);
    $request["connected"] = $parse['success'];
    
    return redirect()->route('site.store.lists', $request);
  }
}
