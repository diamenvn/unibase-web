<?php

namespace App\Http\Controllers\Site;

use App\Helpers\App;
use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Carbon\Carbon;

class SiteProductController extends Controller
{
  public function __construct(CustomerService $customer)
  {
    $this->customer = $customer;
    $this->timeNow = Carbon::now();
  }

  public function create()
  {
    $info = $this->customer->info()
      ->load('product')
      ->load('company')
      ->load('source');
    $form['store'] = route("api.product.store");
    $form['method'] = "post";

    return view('site.product.create')->with('info', $info)->with('form', $form);
  }
}
