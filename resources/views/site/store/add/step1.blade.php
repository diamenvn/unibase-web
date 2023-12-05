@extends('site.layout.master')
@section('title', 'Thêm mới khách hàng')
@php $id = random_int(100000, 999999); @endphp
@section('content')
    <div v-page-id="{{$id}}" class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="main-body flex flex-column py-15px">
                    <div class="page">
                        <div class="page-header">
                            <div class="fs-20"><strong>Thêm kết nối mới đến store</strong></div>
                            <div class="page-subtitle mt-0">Chọn loại store bên dưới</div>
                        </div>
                        <div class="page-body">
                            <div class="my-30px">
                                <div class="row">
                                    <div class="col-12">
                                        <ul id="progressbar">
                                            <li class="active"><span>Kết nối đến store</span></li>
                                            <li><span>Đồng bộ cài đặt</span></li>
                                            <li><span>Hoàn thành</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div v-active-element="#{{$id}}" v-click="chooseApp" v-data="tiktok" class="col-4 d-flex justify-content-center">
                                        <div class="thirdparty-icon">
                                            <img src="{{ asset('assets/site/theme/images/store/tiktok-shop.png') }}"
                                                alt="" srcset="">
                                        </div>
                                    </div>
                                    <div v-active-element="#{{$id}}" v-click="chooseApp" v-data="shopee" v-disabled
                                        class="col-4 d-flex justify-content-center">
                                        <div class="thirdparty-icon disabled">
                                            <img src="{{ asset('assets/site/theme/images/store/shopee.png') }}"
                                                alt="" srcset="">
                                        </div>
                                    </div>
                                    <div v-active-element="#{{$id}}" v-click="chooseApp" v-data="etsy" v-disabled
                                        class="col-4 d-flex justify-content-center">
                                        <div class="thirdparty-icon disabled">
                                            <img src="{{ asset('assets/site/theme/images/store/etsy.png') }}" alt=""
                                                srcset="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end align-items-center w-100">
                                        <a id="{{$id}}" v-click="{{$callAjaxModal}}" v-display-mode="update" v-modal-align="center" width="600px" href=""
                                            class="btn btn-primary pointer fw-600 ml-2 disabled"><span>Tiếp tục</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        var url = '{{ route("site.store.form") }}';
        $(function() {
            $(document).on("click", "[v-click=chooseApp]", function(e) {
                self = $(this);
                if (isDisabled(self)) return;

                self.find(".thirdparty-icon").addClass("active");
                appType = self.attr("v-data");
                self.closest("[v-page-id='{{$id}}']").find("#{{$id}}").attr('href', `${url}?app=${appType}&step=2`);
            });
        });
    </script>
@endsection
