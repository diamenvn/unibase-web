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
                            <div class="fs-20"><strong>Cài đặt đồng bộ dữ liệu</strong></div>
                            <div class="page-subtitle mt-0">Thiết lập cách đồng bộ dữ liệu về hệ thống, có thể lược bỏ dữ
                                liệu lấy về theo các điều kiện mà bạn đặt ra</div>
                        </div>
                        <div class="page-body">
                            <div class="my-30px">
                                <div class="row">
                                    <div class="col-12">
                                        <ul id="progressbar">
                                            <li class="active"><span>Kết nối đến store</span></li>
                                            <li class="active"><span>Đồng bộ cài đặt</span></li>
                                            <li><span>Hoàn thành</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mt-30px">
                                        <div class="box border border-radius-5px p-3">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <div class="text-sm-800 fs-16 fw-600">Sản phẩm</div>

                                                    <div class="text-sm-800 mt-2 fs-14 fw-400">Tự động đồng bộ sản phẩm
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="customSwitches">
                                                        <label class="custom-control-label" for="customSwitches"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box border border-radius-5px p-3 mt-3">
                                            <div class="row align-items-center">
                                                <div class="col-12">
                                                    <div class="text-sm-800 fs-16 fw-600">Đơn hàng</div>
                                                    <div class="text-sm-800 mt-2 fs-14 fw-400">Đồng bộ đơn hàng theo điều
                                                        kiện bên dưới</div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="jumbotron m-0 mt-3 p-0">
                                                        <div class="p-4">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="text-sm-600 fs-14 fw-600">Tên sản phẩm của
                                                                        bạn</div>
                                                                </div>
                                                            </div>
                                                            <form id="product-condition">
                                                                
                                                            </form>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div v-click="add-new-product-condition-item"
                                                                        class="link">Thêm điều kiện</div>
                                                                </div>
                                                            </div>

                                                            <div class="row mt-3">
                                                                <div class="col-12">
                                                                    <div class="text-sm-600 fs-14 fw-600">Category của bạn
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <form id="category-condition">
                                                                
                                                            </form>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div v-click="add-new-category-condition-item"
                                                                        class="link">Thêm điều kiện</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end align-items-center w-100">
                                        <div v-click="{{ $callAjaxModal }}" v-display-mode="update" width="600px"
                                            href="{{ route('site.store.form') }}" class="btn btn-secondary pointer fw-600">
                                            <span>Quay lại</span>
                                        </div>
                                        <div v-click="finish" class="btn btn-primary pointer fw-600 ml-2"><span>Bắt
                                                đầu sử dụng</span></a>
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
        $(function() {
            $(document).on('click', '[v-click="add-new-category-condition-item"]', function(e) {
                var box = $("#category-condition");
                var html = `<div id="category-condition">
                                                                <div class="row condition-item my-2 align-items-center">
                                                                    <div class="col-3">
                                                                        <select name="category-condition-type[]" id="" class="form-control form-control-sm">
                                                                            <option value="0">Equal</option>
                                                                            <option value="1">Like</option>
                                                                            <option value="2">Start with</option>
                                                                            <option value="3">End with</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input name="category-condition-value[]" id="" class="form-control form-control-sm" />
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <span v-click="remove-condition-item"><i class="fas fa-trash"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>`;
                box.append(html);
            });

            $(document).on('click', '[v-click="add-new-product-condition-item"]', function(e) {
                var box = $("#product-condition");
                var html = `<div id="product-condition">
                                                                <div class="row condition-item my-2 align-items-center">
                                                                    <div class="col-3">
                                                                        <select name="product-condition-type[]" id="" class="form-control form-control-sm">
                                                                            <option value="0">Equal</option>
                                                                            <option value="1">Like</option>
                                                                            <option value="2">Start with</option>
                                                                            <option value="3">End with</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input name="product-condition-value[]" id="" class="form-control form-control-sm" />
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <span v-click="remove-condition-item"><i class="fas fa-trash"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>`;
                box.append(html);
            });

            $(document)
            .on('click', '[v-click="remove-condition-item"]', function(e) {
                $(this).closest(".condition-item").remove();
            })
            .on('click', '[v-click="finish"]', function(e) {
                var formProduct = $("#product-condition");
                var formProductToArray = formProduct.serializeArray();
                var formCategory = $("#category-condition");
                var formCategoryToArray = formCategory.serializeArray();
                var data = {
                    tiktokShopId: "{{$request['shopId'] ?? ''}}",
                    categoryFilters: [],
                    productFilters: []
                };

                for (let index = 0; index < formProductToArray.length; index += 2) {
                    data = {
                        ...data,
                        productFilters: [
                            ...data.productFilters,
                            {
                                type: parseInt(formProductToArray[index].value),
                                value: formProductToArray[index + 1].value
                            }
                        ]
                    }
                }

                for (let index = 0; index < formCategoryToArray.length; index += 2) {
                    data = {
                        ...data,
                        categoryFilters: [
                            ...data.productFilters,
                            {
                                type: parseInt(formCategoryToArray[index].value),
                                value: formCategoryToArray[index + 1].value
                            }
                        ]
                    }
                }


                lib.send.post("{{route('api.store.update-setting-condition')}}", function(res) {
                    Notify.show.success(res.msg);
                    lib.removeParams('code');
                    lib.removeParams('connected');
                    $("#modal").modal('hide');
                }, data);
                

                
            })
        });
    </script>
@endsection
