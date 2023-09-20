<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('site.layout.css')
    @include('site.layout.js')
    <title>Đăng nhập</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="main main--layout w-100">
        <div class="layout__bg-wrapper login h-100">
            <div class="container-fluid">
                <div class="row full-height-vh m-0">
                    <div class="col-12 d-flex justify-content-center align-items-center">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body login-img">
                                    <form action="{{route('auth.postLogin')}}" method="POST" class="row m-0">
                                        <div class="col-lg-6 d-lg-block d-none py-2 px-3 text-center align-middle"><img alt="" class="img-fluid mt-5" height="230" src="{{asset('assets/site/theme/images/login.png')}}" width="400"></div>
                                        <div class="col-lg-6 col-md-12 bg-white px-4 pt-3">
                                            <h4 class="card-title mb-2">Đăng nhập</h4>
                                            <p class="card-text mb-4">Để sử dụng phần mềm, bạn vui lòng đăng nhập</p><input class="form-control mb-3" name="username" placeholder="Tài khoản" type="text"><input name="password" class="form-control mb-3" placeholder="Mật khẩu" type="password">
                                            <div class="d-flex justify-content-between mt-">
                                                <div class="remember-me">
                                                    <div class="custom-control custom-checkbox custom-control-inline mb-3"><input class="custom-control-input" id="customCheckboxInline1" name="customCheckboxInline1" type="checkbox"><label class="custom-control-label" for="customCheckboxInline1"> Ghi nhớ mật khẩu </label></div>
                                                </div>
                                                <div class="forgot-password-option"><a class="text-decoration-none text-primary" href="#">Quên mật khẩu</a></div>
                                            </div>
                                            <div class="fg-actions d-flex justify-content-end">
                                                <div class="recover-pass">
                                                    <div class="btn btn-primary btn-login-js pointer"><a class="text-decoration-none text-white">Đăng nhập</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @if (!empty($error))
                                <div class="bottom bg-danger fs-14 p-1 px-2 co-white">
                                    {{$error}}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>