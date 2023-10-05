@extends('site.layout.master')
@section('title', 'Tạo mới sản phẩm')

@section('content')
<div class="app-content">
    <div class="section p-0">
        <div class="main-body flex flex-column">
            <form action="{{$form['store'] ?? ''}}" method="{{$form['method'] ?? 'POST'}}" class="row row--custom h-100 overflow-auto" id="form-product">
                <div class="order-detail--item col-md-8 col-12 h-100 col--custom">
                    <div class="order-detail__title"><i class="fal fa-info-circle mr-1"></i>Thông tin sản phẩm</div>
                    <div class="order-detail__body">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <strong>Lưu ý:</strong> Dòng có dấu <span class="co-red">*</span> là những trường
                                    bắt buộc điền!
                                </div>
                            </div>
                            <div class="col-12">
                                @include('site.uikit.input.text', ['label' => "Tên sản phẩm", 'model' => "product_name", 'require' => true])
                            </div>
                            <div class="col-12">
                                @include('site.uikit.upload.image', ['label' => "Ảnh sản phẩm", 'model' => "product_images", 'require' => true])
                            </div>
                            <div class="col-6">
                                @include('site.uikit.input.text', ['label' => "Chất liệu", 'model' => "product_material"])
                            </div>
                            <div class="col-6">
                                @include('site.uikit.input.text', ['label' => "Màu sắc", 'model' => "product_color"])
                            </div>
                            <div class="col-6">
                                @include('site.uikit.input.text', ['label' => "Kích thước", 'model' => "product_size"])
                            </div>
                            <div class="col-6">
                                @include('site.uikit.input.text', ['label' => "Tổng khối lượng", 'model' => "product_weight"])
                            </div>
                            <div class="col-6">
                                @include('site.uikit.input.currency', ['label' => "Giá sản phẩm", 'model' => "product_cost", 'require' => true])
                            </div>
                            <div class="col-6">
                                @include('site.uikit.input.currency', ['label' => "Giá ship", 'model' => "product_ship_price"])
                            </div>
                            <div class="col-6">
                                @include('site.uikit.input.currency', ['label' => "Phí xử lý đơn", 'model' => "product_process_price"])
                            </div>
                            <div class="col-6">
                                @include('site.uikit.input.text', ['label' => "Mã SKU", 'model' => "product_sku_name"])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-detail--item col-md-4 col-12 h-100 col--custom">
                    <div class="order-detail__title">Tình trạng sản phẩm</div>
                    <div class="order-detail__body">
                        <div class="row">
                            <div class="col-12">
                                @include('site.uikit.input.number', ['label' => "Tổng số lượng đơn", 'model' => "total_all_order"])
                            </div>
                            <div class="col-12">
                                @include('site.uikit.input.number', ['label' => "Số lượng đã xử lý", 'model' => "total_order_proccessed"])
                            </div>
                            <div class="col-12">
                                @include('site.uikit.input.number', ['label' => "Số lượng đơn chờ xử lý", 'model' => "total_order_wait_process"])
                            </div>
                            <div class="col-12">
                                @include('site.uikit.input.number', ['label' => "Số lượng đơn đang xử lý", 'model' => "total_order_proccessing"])
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row row--custom">
                <div class="col--custom col-12">
                    <div class="bg-white d-flex justify-content-end align-items-center w-100 p-2">
                        @include('site.uikit.button.save', ['form' => '#form-product'])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection