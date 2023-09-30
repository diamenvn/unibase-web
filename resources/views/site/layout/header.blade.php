<nav class="navbar navbar-header navbar-light">
    <div class="navbar-menu">
        <div class="navbar-item flex-center float-right">
            <div class="icon flex-center">
                <i class="fal fa-envelope"></i>
            </div>
            <div class="icon flex-center">
                <i class="fal fa-bell"></i>
            </div>
            <div class="user flex-center position-relative">
                <i class="fal fa-user co-green"></i> <span class="ml-2 co-default">{{$user->name}}</span>
                <div class="list-item-user position-absolute">
                    <ul class="m-0">
                        <li><a class="d-block" href="{{route('site.user.create')}}"><i class="fal fa-user mr-2"></i>Thông tin cá nhân</a></li>
                        <li><a class="d-block" href="{{route('site.auth.logout')}}"><i class="fal fa-sign-out-alt mr-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>