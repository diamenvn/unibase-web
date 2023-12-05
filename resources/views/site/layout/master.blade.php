@if (app('request')->input('popup') == 'true')
    <div class="app-content">
        @yield('content')
    </div>
    @yield('handle_response')
    @yield('custom_js')
    @yield('modal')
@else
    <!DOCTYPE html>
    <html lang="en" dir="ltr">

    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" type="image/png" href="{{ asset('assets/site/theme/images/logo.ico') }}" />
        <title>@yield('title')</title>
        @include('site.layout.js')
        @include('site.layout.css')
    </head>

    <body>
        <div class="wraper wraper--layout">
            @include('site.modules.header.index')
            <main class="mainbody">
                @include('site.layout.aside.index')
                <div class="main">
                    @if (isset($useTopHeader) && $useTopHeader == 'light')
                        @include('site.modules.header.light')
                    @endif
                    <div class="app-content">
                        @yield('content')
                    </div>
                </div>
                @yield('modal')
            </main>
            @include('site.modules.button.quick-action')
            <div class="modal-backdrop fade d-none"></div>
        </div>
    </body>

    </html>
    @yield('js')
    @yield('lib_js')
    @yield('handle_response')
    @yield('custom_js')
    @include('site.layout.modal.index')
@endif
