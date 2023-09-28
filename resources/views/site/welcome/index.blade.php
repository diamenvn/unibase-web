<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @include('site.layout.css')
        @include('site.layout.js')
        <title>Welcome - Unibase.vn</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body>
        <div class="welcome main main--layout w-100">
            <div class="welcome--background h-100">
                <div class="welcome--layout"></div>
            </div>
            <div class="welcome__page full-height-vh d-flex flex-column">
                <div class="container-fluid">
                    <div class="row align-items-center py-1 m-0">
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <span class="mx-1">@include('site.uikit.logo', ['color' => 'rgba(255, 255, 255, 0.6)'])</span>
                                <span class="text-desc mx-1">Công ty TNHH TEDY Việt Nam</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="navbar">
                                <div class="d-flex justify-content-end w-100">
                                    <div class="navbar-item flex-center float-right">
                                        <div class="icon flex-center">
                                            <i class="fal fa-bell"></i>
                                        </div>
                                        <div class="icon user flex-center position-relative">
                                            <i class="fal fa-user fs-18"></i> <span class="ml-2">Nguyễn Văn Nam</span>
                                            <div class="list-item-user position-absolute">
                                                <ul class="m-0">
                                                    <li><a class="d-block" href="http://localhost:8000/users/create"><i class="fal fa-user mr-2"></i>Thông tin cá nhân</a></li>
                                                    <li><a class="d-block" href="http://localhost:8000/logout"><i class="fal fa-sign-out-alt mr-2"></i>Đăng xuất</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container flex-1">
                    <div class="pt-5 px-5 pb-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-center">
                                    <div class="welcome__block__search">
                                        <i class="welcome__block__search__icon fal fa-search"></i>
                                        <input type="text" class="welcome__block__search__input"
                                            placeholder="Tìm kiếm ứng dụng">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                @include('site.welcome.app')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            @include('site.welcome.date')
                        </div>
                        <div class="col-8">
                            @include('site.welcome.notify')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>