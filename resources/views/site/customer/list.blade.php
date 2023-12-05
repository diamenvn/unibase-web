@extends('site.layout.master')
@section('title', 'Danh sách sản phẩm')

@php(
    $columns = [
        [
            'title' => 'Mã khách hàng',
            'name' => 'makh',
            'default' => "KH-001"
        ],
        [
            'title' => 'Tên khách hàng',
            'name' => 'name'
        ],
        [
            'title' => 'Email khách hàng',
            'name' => 'email'
        ],
        [
            'title' => 'Số điện thoại',
            'name' => 'phone'
        ],
        [
            'title' => 'Số lượng đơn đã đặt',
            'default' => 0,
            'align' => 'right'
        ],
        [
            'title' => 'Số lượng đơn đã hoàn thành',
            'default' => 0,
            'align' => 'right'
        ],
        [
            'title' => 'Số tiền đã thanh toán',
            'default' => 0,
            'align' => 'right'
        ],
        [
            'title' => 'Thao tác',
        ],
    ]
)

@php(
    $dataTable = [
        'columns' => $columns,
        'tabs' => $tabs,
        'config' => [
            'checkboxAll' => true,
            'id' => '_id',
            'detail_url' => route("site.customer.detail", ':id') 
        ],
        'search' => [
            'placeholder' => 'Tìm kiếm tên khách hàng, số điện thoại, email'
        ]
    ]
)

@section('content')
    <div class="app-content">
        <div class="section flex-1">
            <div class="page main-body flex flex-column">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page__header">
                                <div class="flex-1">
                                    <h6 class="page-title mb-1">
                                        <strong>Danh sách khách hàng</strong>
                                    </h6>
                                    <span class="page-subtitle">Với những cửa hàng có hàng trăm sản phẩm, cập nhật thủ công từng thông
                                        tin mô tả sản phẩm, giá bán và giá gốc sẽ tốn rất nhiều thời gian và công sức. Công cụ cập nhật
                                        hàng loạt sẽ giúp bạn năng suất hơn.</span>
                                </div>
                                <div>
                                    @if ($user->permission == 'admin')
                                        <div class="btn btn-danger fs-13 btn-click-remove-js pointer">Xoá khách hàng</div>
                                    @endif
                                    <a v-click="{{ $callAjaxModal }}" width="1400px" href="{{route('site.customer.create')}}" class="btn btn-primary fs-13 pointer"><span>Thêm khách
                                        hàng</span></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            @include('site.uikit.table.light', $dataTable)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection