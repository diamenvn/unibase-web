@extends('site.layout.master')
@section('title', 'Danh sách sản phẩm')


@php(
    $columns = [
        [
            'title' => 'Tên sản phẩm',
            'name' => 'productName'
        ],
        [
            'title' => 'Nguồn sản phẩm',
            'name' => '',
            'default' => 'Tiktok'
        ],
        [
            'title' => 'Danh mục',
            'name' => 'categoryName',
            'default' => ''
        ],
        [
            'title' => 'Ảnh sản phẩm',
            'name' => 'images',
            'type' => 'image',
            'align' => 'center'
        ],
        [
            'title' => 'Chất liệu',
            'name' => 'product_material'
        ],
        [
            'title' => 'Màu sắc',
            'name' => 'product_color'
        ],
        [
            'title' => 'Kích thước',
            'name' => 'packageWidth'
        ],
        [
            'title' => 'Tổng khối lượng',
            'name' => 'packageWeight'
        ],
        [
            'title' => 'Giá bán sản phẩm',
            'name' => 'product_price'
        ],
        [
            'title' => 'Phí ship',
            'name' => 'product_ship_price'
        ],
        [
            'title' => 'Mã SKU',
            'name' => 'product_sku_name'
        ],
        [
            'title' => 'Chi tiết'
        ],
    ]
)

@php(
    $dataTable = [
        'columns' => $columns,
        'tabs' => $tabs,
        'config' => [
            'checkboxAll' => true,
        ],
        'search' => [
            'placeholder' => 'Tìm kiếm sản phẩm',
        ],
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
                                        <strong>Danh sách sản phẩm</strong>
                                    </h6>
                                    <span class="page-subtitle">Danh sách các cửa hàng bên thứ 3 đang kết nối, có thể đồng bộ tất cả sản phẩm và đơn hàng vào đây sau khi đã kết nối. Nhấn thêm mới góc bên phải để thêm 1 kết nối mới và đồng bộ vào hệ thống</span>
                                </div>
                                <div>
                                    @if ($user->permission == 'admin')
                                        <div class="btn btn-primary btn-large fs-13 pointer"> <a href="{{ route('site.product.create') }}">Tạo sản phẩm</a> </div>
                                    @endif
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

@section('handle_response')
<script>
    function handleResponse(row) {
        return {
            ...row,
            images: row?.images?.[0]?.thumbUrlList?.[0] ?? "",
            product_price: parseInt((row?.skus?.[0]?.price?.originalPrice ?? 0)).toLocaleString('it-IT', {style : 'currency', currency : row?.skus?.[0]?.price?.currency ?? "VND"}),
            product_sku_name: row?.skus?.[0]?.salesAttributes?.[0].id ?? "",
            categoryName: row?.categoryList?.[0]?.localDisplayName ?? ""
        }
    }
</script>
@endsection