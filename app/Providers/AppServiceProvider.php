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
                        'url' => '/'
                    ]
                ]
            ],
            [
                'title' => 'Sản phẩm',
                'items' => [
                    [
                        'label' => 'Tạo sản phẩm',
                        'url' => '/'
                    ],
                    [
                        'label' => 'Danh sách sản phẩm',
                        'url' => '/'
                    ],
                    [
                        'label' => 'Import từ excel',
                        'url' => '/'
                    ],
                ]
            ],
            [
                'title' => 'Đơn hàng',
                'items' => [
                    [
                        'label' => 'Tạo đơn hàng',
                        'url' => '/'
                    ],
                    [
                        'label' => 'Danh sách đơn hàng',
                        'url' => '/'
                    ],
                    [
                        'label' => 'Import từ excel',
                        'url' => '/'
                    ],
                ]
            ],
            [
                'title' => 'Khách hàng',
                'items' => [
                    [
                        'label' => 'Tạo khách hàng',
                        'url' => '/'
                    ],
                    [
                        'label' => 'Danh sách',
                        'url' => '/'
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
