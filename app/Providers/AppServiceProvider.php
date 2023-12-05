<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       
        $asideMenuItems = [
            [
                'icon' => 'fas fa-chart-bar',
                'title' => 'Thống kê',
                'uri' => '/dashboard'
            ],
            [
                'title' => 'Sản phẩm',
                'icon' => 'fas fa-shopping-bag',
                'uri' => '/product/lists'
            ],
            [
                'title' => 'Đơn hàng',
                'icon' => 'fas fa-tags',
                'uri' => '/order/lists'
            ],
            [
                'title' => 'Khách hàng',
                'icon' => 'fas fa-users',
                'uri' => '/customer/lists'
            ],
            [
                'title' => 'Cửa hàng',
                'icon' => 'fas fa-store',
                'uri' => '/store/lists'
            ]
        ];

        $events = [
            "callAjaxModal" => "call-ajax-modal-js",
            "callAjaxReplaceContent" => "call-ajax-repalce-content-js",
            "filterStatus" => "filter-status",
        ];

        // $linkInQuickAction = [
        //     "create-store" => route("api.customer.getListCustomer")
        // ];
        

        view()->composer('*',function($view) use($asideMenuItems, $events) {
            $view->with('user', Auth::user());
            $view->with('asideMenuItems', $asideMenuItems);
            $view->with('callAjaxModal', $events["callAjaxModal"]);
            $view->with('filterStatus', $events["filterStatus"]);
            $view->with('callAjaxReplaceContent', $events["callAjaxReplaceContent"]);
        });
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
