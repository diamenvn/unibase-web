<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="shortcut icon" type="image/png" href="{{asset('assets/site/theme/images/favicon.ico')}}" />
  <title>@yield('title')</title>
  @include('site.layout.js')
  @include('site.layout.css')
</head>

<body>
  <div class="wraper wraper--layout">
    <nav class="navbar navbar-header navbar-light">
      <div class="navbar-brand flex-center">
        <div id="logo"></div>
      </div>
      <div class="navbar-menu">
        <div class="navbar-item float-left">
          <div class="text time-expire">Hạn dùng: 20/11/2021</div>
          <div class="text text-upcase co-green bold">Gói dịch vụ: gói doanh nghiệp 6 tháng</div>
        </div>
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
    <main class="mainbody">
      <div class="sidebar">
        <div class="sidebar-width sidebar-style d-flex">
          <div class="first-sidemenu">
            <ul>
              <li>
                <a class="sidebar-item {{strpos(Route::current()->getName(), 'home') !== false ? 'active' : ''}}" href="{{route('site.home.dashboard')}}">
                  <span><i class="fal fa-tachometer"></i></span>
                  <div>Trang chủ</div>
                </a>
              </li>
              @if ($user->type_account != "care")
              <li>
                <a class="sidebar-item {{strpos(Route::current()->getName(), 'site.order') !== false ? 'active' : ''}}" href="{{route('site.order.list')}}">
                  <span><i class="fal fa-users"></i></span>
                  <div>Khách hàng</div>
                </a>
                <div class="sidebar-menu-open">
                  <div class="menu-open__header fs-18">
                    <i class="fal fa-users"></i> Đơn hàng
                  </div>
                  <div class="menu-open__body fs-15">
                    <ul>
                      @if($user->type_account != "care")
                        @if ($user->type_account == "mkt")
                        <li><a href="{{route('site.order.create')}}"><i class="fal fa-plus"></i> Tạo đơn mới</a></li>
                        @endif
                        <li><a href="{{route('site.order.list')}}"><i class="fal fa-list"></i> Kho số chung</a></li>
                        <li><a href="{{route('site.order.mylist')}}"><i class="fal fa-list"></i> Đơn hàng của tôi</a></li>
                      @endif
                    </ul>
                  </div>
                </div>
              </li>
              @endif
              @if ($user->type_account == "care" || ($user->type_account == "mkt" && $user->permission == "admin"))
              <li>
                <a class="sidebar-item {{strpos(Route::current()->getName(), 'site.care') !== false ? 'active' : ''}}" href="{{route('site.care')}}">
                  <span><i class="fal fa-users"></i></span>
                  <div>CSKH</div>
                </a>
                <div class="sidebar-menu-open">
                  <div class="menu-open__header fs-18">
                    <i class="fal fa-users"></i> Danh sách
                  </div>
                  <div class="menu-open__body fs-15">
                    <ul>
                      <li><a href="{{route('site.care')}}"><i class="fal fa-list"></i> Chăm sóc khách hàng</a></li>
                    </ul>
                  </div>
                </div>
              </li>
              @endif
              
              <li>
                <a class="sidebar-item {{strpos(Route::current()->getName(), 'site.setting') !== false ? 'active' : ''}}" href="#">
                  <span><i class="fal fa-cogs"></i></span>
                  <div>Thiết lập</div>
                </a>
                <div class="sidebar-menu-open">
                  <div class="menu-open__header fs-18">
                    <i class="fal fa-address-book"></i> Thiết lập
                  </div>
                  <div class="menu-open__body fs-15">
                    <ul>
                      @if ($user->permission == "admin")
                        <li><a href="{{route('site.setting.group')}}"><i class="fal fa-list"></i> Chia nhóm</a></li>
                      @endif
                      @if ($user->permission == "admin" && $user->type_account == "mkt")
                        <li><a href="{{route('site.setting.addProduct')}}"><i class="fal fa-list"></i> Quản lý sản phẩm</a></li>
                      @endif
                      @if ($user->type_account == "mkt")
                        <li><a href="{{route('site.setting.import.api')}}"><i class="fal fa-list"></i> Tự động nhập Data</a></li>
                      @endif
                    </ul>
                  </div>
                </div>
              </li>
              <!--<li>-->
              <!--  <a class="sidebar-item {{strpos(Route::current()->getName(), 'site.report') !== false ? 'active' : ''}}" href="{{route('site.report.billing')}}">-->
              <!--    <span><i class="fal fa-chart-line"></i></span>-->
              <!--    <div>Báo cáo</div>-->
              <!--  </a>-->
              <!--  <div class="sidebar-menu-open">-->
              <!--    <div class="menu-open__header fs-18">-->
              <!--      <i class="fal fa-address-book"></i> Báo cáo-->
              <!--    </div>-->
              <!--    <div class="menu-open__body fs-15">-->
              <!--      <ul>-->
              <!--        @if ($user->permission == "admin" && $user->type_account == "mkt")-->
              <!--          <li><a href="{{route('site.report.billing')}}"><i class="fal fa-list"></i> Doanh thu marketing</a></li>-->
              <!--        @endif-->
              <!--        @if ($user->permission == "admin")-->
              <!--          <li><a href="{{route('site.report.billingSale')}}"><i class="fal fa-list"></i> Doanh thu sale</a></li>-->
              <!--        @endif-->
              <!--        <li><a href="{{route('site.report.note')}}"><i class="fal fa-list"></i> Ghi chú của sale</a></li>-->
              <!--        <li><a href="{{route('site.report.personal')}}"><i class="fal fa-list"></i> Báo cáo cá nhân</a></li>-->
              <!--      </ul>-->
              <!--    </div>-->
              <!--  </div>-->
              <!--</li>-->
              <li>
                <a class="sidebar-item {{strpos(Route::current()->getName(), 'site.user') !== false ? 'active' : ''}}" href="#">
                  <span><i class="fal fa-user"></i></span>
                  <div>Người dùng</div>
                </a>
                <div class="sidebar-menu-open">
                  <div class="menu-open__header fs-18">
                    <i class="fal fa-user"></i> Người dùng
                  </div>
                  <div class="menu-open__body fs-15">
                    <ul>
                      <li><a href="{{route('site.user.create')}}"><i class="fal fa-list"></i> Thông tin cá nhân</a></li>
                      <li><a href="{{route('site.user.create')}}"><i class="fal fa-list"></i> Tạo tài khoản</a></li>
                    </ul>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="main">
        @yield('content')
      </div>
      @yield('modal')
    </main>
  </div>
</body>

</html>
@yield('js')
@yield('lib_js')
@yield('custom_js')