@extends('site.layout.master')
@section('title', 'Thêm mới khách hàng')
@php $id = random_int(100000, 999999); @endphp
@section('content')
    <div v-page-id="{{ $id }}" class="container-fluid">
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
                                    <div class="col-12 mt-30px">
                                        <ul class="default">
                                            <li class="text-sm-600 fs-15">Kết nối đến cửa hàng Tiktok Shop của bạn để đồng
                                                bộ mọi sản phẩm và đơn hàng.</li>
                                            <li class="text-sm-800 fs-16">Khi có câu hỏi <strong>khoảng thời gian</strong>
                                                trong lúc xác thực, bạn nên chọn là 1 năm</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end align-items-center w-100">
                                        <div v-click="{{ $callAjaxModal }}" v-modal-align="center" v-display-mode="update" width="600px"
                                            href="{{ route('site.store.form') }}" class="btn btn-secondary pointer fw-600">
                                            <span>Quay lại</span></div>
                                        <a href="{{ $sso_url }}"
                                            class="btn btn-primary pointer fw-600 ml-2"><span>Kết nối</span></a>
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
        var url = '{{ route('site.store.form') }}';
        $(function() {
            $(document).on("click", "[v-click=chooseApp]", function(e) {
                self = $(this);
                if (isDisabled(self)) return;

                self.find(".thirdparty-icon").addClass("active");
                appType = self.attr("v-data");
                self.closest("[v-page-id='{{ $id }}']").find("#{{ $id }}").attr('href',
                    url + "?app=" + appType);
            });
        });
    </script>
@endsection
