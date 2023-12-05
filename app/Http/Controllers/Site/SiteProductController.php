<?php

namespace App\Http\Controllers\Site;

use App\Helpers\App;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\CatalogService;
use Carbon\Carbon;

class SiteProductController extends Controller
{
  public function __construct(UserService $user, CatalogService $catalogService)
  {
    $this->catalogService = $catalogService;
    $this->user = $user;
    $this->timeNow = Carbon::now();
  }

  public function create()
  {
    $info = $this->user->info()
      ->load('product')
      ->load('company')
      ->load('source');
    $form['store'] = route("api.product.store");
    $form['method'] = "post";

    return view('site.product.create')->with('info', $info)->with('form', $form);
  }

  public function detail($id)
  {
    $info = $this->user->info()
      ->load('product')
      ->load('company')
      ->load('source');
    $form['store'] = route("api.product.store");
    $form['method'] = "post";

    $data = $this->catalogService->firstById($id);

    return view('site.product.create')->with('info', $info)->with('form', $form)->with('data', $data);
  }

  public function list()
  {
    $data['sources'] = [];
    $data['info'] = $this->user->info()
      ->load('product')
      ->load('company')
      ->load('source');
    $data['tabs'] = $this->getListTabs();
    $data['route_list'] = route('api.product.getListProduct');

    return view('site.product.list', $data);
  }

  public function getListTabs()
  {
    return [
        [
            'title' => 'Tất cả',
            'value' => 'all',
        ],
        [
            'title' => 'Tiktok',
            'value' => 'tiktok',
        ],
        [
            'title' => 'Shopee',
            'value' => 'shopee',
        ],
        [
            'title' => 'Etsy',
            'value' => 'etsy',
        ],
        [
            'title' => 'Lazada',
            'value' => 'lazada',
        ]
        ];
    }
}
