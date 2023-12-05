<div class="sidebar">
    <div class="sidebar-style d-flex">
        <div class="second-sidemenu">
            <div class="sidemenu__style">
                <div class="aside__lists__menu">
                    @foreach($asideMenuItems as $block)
                    @include('site.layout.aside.aside-block', $block)
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>