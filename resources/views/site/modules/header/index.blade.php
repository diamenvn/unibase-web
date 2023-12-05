<nav class="navbar navbar-header navbar-light">
    <div class="navbar-menu">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col">
                    <div class="row">
                        <div class="col-auto">
                            <div class="navbar-menu__section">
                                <div class="d-flex align-items-center">
                                    @include('site.uikit.logo', [
                                        'color' => '#5966cd',
                                        'width' => '30px',
                                        'height' => '30px',
                                    ])
                                    <span class="uni-brand-text ml-1">Unibase</span>
                                    <span class="uni-brand-text uni-brand-text--domain">.vn</span>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="navbar-menu__section">
                                <div class="d-flex align-items-center">
                                    <div class="search-container search-full position-relative">
                                        <span class="search-icon"><i class="fal fa-search"></i></span>
                                        <input type="text" class="search-control form-control" placeholder="Tìm kiếm trên ứng dụng">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="navbar-menu__section flex-center float-right">
                        <div class="icon flex-center">
                            <i class="fal fa-envelope"></i>
                        </div>
                        <div class="icon flex-center">
                            <i class="fal fa-bell"></i>
                        </div>
                        <div class="user flex-center position-relative">
                            <i class="fal fa-user co-green"></i> <span
                                class="ml-2 co-default">{{ $user->name }}</span>
                            <div class="list-item-user position-absolute">
                                <ul class="m-0">
                                    <li><a class="d-block"><i class="fal fa-user mr-2"></i>Thông tin
                                            cá nhân</a></li>
                                    <li><a class="d-block" href="{{ route('site.auth.logout') }}"><i
                                                class="fal fa-sign-out-alt mr-2"></i>Đăng xuất</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
