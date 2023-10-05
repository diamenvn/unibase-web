<div class="sidebar">
    <div class="sidebar-style d-flex">
        <div class="first-sidemenu d-flex flex-column">
            <ul class="flex-1">
                <li>
                    <a class="sidebar-item" href="{{route('site.home.welcome')}}">
                        <span>
                            @include('site.uikit.logo', ['color' => '#fff', 'width' => '25px', 'height' => '25px'])
                        </span>
                    </a>
                </li>
                <li>
                    <a class="sidebar-item" href="{{route('site.product.lists')}}">
                        <span><i class="fal fa-tag"></i></span>
                    </a>
                </li>
                <li>
                    <a class="sidebar-item" href="{{route('site.order.lists')}}">
                        <span><i class="fal fa-shopping-bag"></i></span>
                    </a>
                </li>
            </ul>
            <ul>
                <li>
                    <a class="sidebar-item" href="{{route('site.user.lists')}}">
                        <span><i class="fal fa-user"></i></span>
                    </a>
                </li>
                <li>
                    <a class="sidebar-item" href="{{route('site.order.lists')}}">
                        <span><i class="fal fa-ellipsis-h fs-25"></i></span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="second-sidemenu">
            <div class="sidemenu__style">
                <div class="sidemenu__profile py-15px">
                    <div class="d-flex align-items-center">
                        <div class="profile__avatar">
                            <img src="{{asset('assets/site/theme/images/gamer.png')}}" alt="" srcset="" />
                        </div>
                        <div class="profile__info">
                            <div class="profile__info__name mx-2 fw-600 fs-14">
                                {{$user->name}}
                            </div>
                            <div class="profile__info__role color-text-grey mx-2">
                                {{$user->permission}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="aside__search">
                    <div class="welcome__block__search">
                        <i class="welcome__block__search__icon fal fa-search"></i>
                        <input type="text" class="welcome__block__search__input" placeholder="Tìm thông tin" />
                    </div>
                </div>
                <div class="aside__lists__menu">
                    @foreach($asideMenuItems as $block)
                    @include('site.layout.aside.aside-block', $block)
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>