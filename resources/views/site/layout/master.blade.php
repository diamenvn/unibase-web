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
          <a id="logo">
            <svg class="logo-abbr" width="39" height="23" viewBox="0 0 39 23" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path class="w3"
                d="M32.0362 22H19.0466L20.7071 18.7372C20.9559 18.2484 21.455 17.9378 22.0034 17.9305L31.1036 17.8093C33.0753 17.6497 33.6571 15.9246 33.7015 15.0821C33.7015 13.2196 32.1916 12.5765 31.4367 12.4878H23.7095L25.9744 8.49673H30.4375C31.8763 8.3903 32.236 7.03332 32.236 6.36814C32.3426 4.93133 30.9482 4.61648 30.2376 4.63865H28.6955C28.2646 4.63865 27.9788 4.19212 28.1592 3.8008L29.7047 0.44798C31.0903 0.394765 32.8577 0.780573 33.5683 0.980129C38.6309 3.42801 37.0988 7.98676 35.6999 9.96014C38.1513 11.9291 38.4976 14.3282 38.3644 15.2816C38.098 20.1774 34.0346 21.8005 32.0362 22Z"
                fill="#269c71"></path>
              <path class="react-w"
                d="M9.89261 21.4094L0 2.80536H4.86354C5.41354 2.80536 5.91795 3.11106 6.17246 3.59864L12.4032 15.5355C12.6333 15.9762 12.6261 16.5031 12.3842 16.9374L9.89261 21.4094Z"
                fill="#269c71"></path>
              <path class="react-w"
                d="M17.5705 21.4094L7.67786 2.80536H12.5372C13.0894 2.80536 13.5954 3.11351 13.8489 3.60412L20.302 16.0939L17.5705 21.4094Z"
                fill="#269c71"></path>
              <path class="react-w"
                d="M17.6443 21.4094L28.2751 0H23.4513C22.8806 0 22.361 0.328884 22.1168 0.844686L14.8271 16.2416L17.6443 21.4094Z"
                fill="#269c71"></path>
              <path class="react-w"
                d="M9.89261 21.4094L0 2.80536H4.86354C5.41354 2.80536 5.91795 3.11106 6.17246 3.59864L12.4032 15.5355C12.6333 15.9762 12.6261 16.5031 12.3842 16.9374L9.89261 21.4094Z"
                stroke="#269c71"></path>
              <path class="react-w"
                d="M17.5705 21.4094L7.67786 2.80536H12.5372C13.0894 2.80536 13.5954 3.11351 13.8489 3.60412L20.302 16.0939L17.5705 21.4094Z"
                stroke="#269c71"></path>
              <path class="react-w"
                d="M17.6443 21.4094L28.2751 0H23.4513C22.8806 0 22.361 0.328884 22.1168 0.844686L14.8271 16.2416L17.6443 21.4094Z"
                stroke="#269c71"></path>
            </svg>
          </a>
        </div>
        <div class="navbar-menu">
          <div class="navbar-item float-left">
            <div class="text time-expire">Hạn dùng: 20/11/2023</div>
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
                  <li><a class="d-block" href="{{route('site.user.create')}}"><i class="fal fa-user mr-2"></i>Thông tin
                      cá nhân</a></li>
                  <li><a class="d-block" href="{{route('site.auth.logout')}}"><i
                        class="fal fa-sign-out-alt mr-2"></i>Đăng xuất</a></li>
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
                  <a class="sidebar-item {{strpos(Route::current()->getName(), 'home') !== false ? 'active' : ''}}"
                    href="{{route('site.home.dashboard')}}">
                    <span><i class="fal fa-tachometer"></i></span>
                    <div>Dashboard</div>
                  </a>
                </li>
                <li>
                  <a class="sidebar-item {{strpos(Route::current()->getName(), 'site.product') !== false ? 'active' : ''}}"
                    href="{{route('site.product.list')}}">
                    <span><i class="fal fa-tag"></i></span>
                    <div>Sản phẩm</div>
                  </a>
                  <div class="sidebar-menu-open">
                    <div class="menu-open__header fs-18">
                      <span>Sản phẩm</span>
                    </div>
                    <div class="menu-open__body fs-15">
                      <ul>
                        <li><a href="{{route('site.product.create')}}"><i class="fal fa-list"></i> Tạo sản phẩm mới</a></li>
                        <li><a href="{{route('site.product.create')}}"><i class="fal fa-list"></i> Import từ excel</a></li>
                        <li><a href="{{route('site.product.list')}}"><i class="fal fa-list"></i> Danh sách sản phẩm</a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </li>
                <!-- <li>
                  <a class="sidebar-item {{strpos(Route::current()->getName(), 'site.order') !== false ? 'active' : ''}}"
                    href="{{route('site.order.list')}}">
                    <span><i class="fal fa-money-bill-alt"></i></span>
                    <div>Báo giá</div>
                  </a>
                  <div class="sidebar-menu-open">
                    <div class="menu-open__header fs-18">
                      <span>Báo giá</span>
                    </div>
                    <div class="menu-open__body fs-15">
                      <ul>
                        <li><a href="{{route('site.order.list')}}"><i class="fal fa-list"></i> Tạo báo giá</a></li>
                        <li><a href="{{route('site.order.mylist')}}"><i class="fal fa-list"></i> Danh sách báo giá</a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </li> -->
                <li>
                  <a class="sidebar-item {{strpos(Route::current()->getName(), 'site.order') !== false ? 'active' : ''}}"
                    href="{{route('site.order.list')}}">
                    <span><i class="fal fa-shopping-bag"></i></span>
                    <div>Đơn hàng</div>
                  </a>
                </li>
                <li>
                  <a class="sidebar-item {{strpos(Route::current()->getName(), 'site.setting') !== false ? 'active' : ''}}"
                    href="#">
                    <span><i class="fal fa-cogs"></i></span>
                    <div>Thiết lập</div>
                  </a>
                  <div class="sidebar-menu-open">
                    <div class="menu-open__header fs-18">
                      <span>Thiết lập</span>
                    </div>
                    <div class="menu-open__body fs-15">
                      <ul>
                        @if ($user->permission == "admin")
                        <li><a href="{{route('site.setting.group')}}"><i class="fal fa-list"></i> Chia nhóm</a></li>
                        @endif
                        @if ($user->permission == "admin" && $user->type_account == "mkt")
                        <li><a href="{{route('site.setting.addProduct')}}"><i class="fal fa-list"></i> Quản lý sản
                            phẩm</a></li>
                        @endif
                        @if ($user->type_account == "mkt")
                        <li><a href="{{route('site.setting.import.api')}}"><i class="fal fa-list"></i> Tự động nhập
                            Data</a></li>
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
                  <a class="sidebar-item {{strpos(Route::current()->getName(), 'site.user') !== false ? 'active' : ''}}"
                    href="#">
                    <span><i class="fal fa-user"></i></span>
                    <div>Tài khoản</div>
                  </a>
                  <div class="sidebar-menu-open">
                    <div class="menu-open__header fs-18">
                      <span>Tài khoản</span>
                    </div>
                    <div class="menu-open__body fs-15">
                      <ul>
                        <li><a href="{{route('site.user.create')}}"><i class="fal fa-list"></i> Thông tin cá nhân</a>
                        </li>
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