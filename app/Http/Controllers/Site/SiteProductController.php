<?php

namespace App\Http\Controllers\Site;

use App\Helpers\App;
use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use App\Services\CatalogService;
use Carbon\Carbon;

class SiteProductController extends Controller
{
  public function __construct(CustomerService $customer, CatalogService $catalogService)
  {
    $this->catalogService = $catalogService;
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

  public function detail($id)
  {
    $info = $this->customer->info()
      ->load('product')
      ->load('company')
      ->load('source');
    $form['store'] = route("api.product.store");
    $form['method'] = "post";

    $data = $this->catalogService->firstById($id);

    return view('site.product.create')->with('info', $info)->with('form', $form)->with('data', $data);
  }
}
