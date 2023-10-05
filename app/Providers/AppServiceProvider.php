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
                'title' => 'Dashboard',
                'items' => [
                    [
                        'label' => 'Báo cáo ngày',
                        'url' => '/report/daily-report'
                    ]
                ]
            ],
            [
                'title' => 'Sản phẩm',
                'items' => [
                    [
                        'label' => 'Tạo sản phẩm',
                        'url' => '/product/create'
                    ],
                    [
                        'label' => 'Danh sách sản phẩm',
                        'url' => '/product/lists'
                    ],
                    [
                        'label' => 'Import từ excel',
                        'url' => '/product/import/excel'
                    ],
                ]
            ],
            [
                'title' => 'Đơn hàng',
                'items' => [
                    [
                        'label' => 'Tạo đơn hàng',
                        'url' => '/order/create'
                    ],
                    [
                        'label' => 'Danh sách đơn hàng',
                        'url' => '/order/lists'
                    ],
                    [
                        'label' => 'Import từ excel',
                        'url' => '/order/import/excel'
                    ],
                ]
            ],
            [
                'title' => 'Khách hàng',
                'items' => [
                    [
                        'label' => 'Tạo khách hàng',
                        'url' => '/customer/create'
                    ],
                    [
                        'label' => 'Danh sách',
                        'url' => '/customer/lists'
                    ]
                ]
            ]
        ];

        view()->composer('*',function($view) use($asideMenuItems) {
            $view->with('user', Auth::user());
            $view->with('asideMenuItems', $asideMenuItems);
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
