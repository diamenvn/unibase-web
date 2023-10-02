<nav class="navbar navbar-header navbar-light">
    <div class="navbar-menu">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="page__title py-2 m-0">@yield('page-title')</h1>
                        </div>
                        <div class="col-12">
                            <ul class="navbar__lists d-flex mb-0">
                                @yield('navigate')
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    @yield('header-right')
                </div>
            </div>
        </div>
    </div>
</nav>