<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" type="image/png" href="{{asset('assets/site/theme/images/logo.ico')}}" />
    <title>@yield('title')</title>
    @include('site.layout.js')
    @include('site.layout.css')
  </head>

  <body>
    <div class="wraper wraper--layout">
      <main class="mainbody">
        @include('site.layout.aside.index')
        <div class="main">
          @if (isset($useTopHeader) && $useTopHeader == "light")
          @include('site.modules.header.light')
          @endif
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