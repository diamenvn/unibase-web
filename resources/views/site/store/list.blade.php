@extends('site.layout.master')
@section('title', 'Danh sách cửa hàng')

@php(
    $columns = [
        [
            'title' => 'Tên',
            'name' => 'shopName',
        ],
        [
            'title' => 'Loại',
            'name' => '',
            'default' => 'Tiktok',
        ],
        [
            'title' => 'Shop code',
            'name' => 'shopCode',
        ],
        [
            'title' => 'Số lượng sản phẩm',
            'align' => 'right',
            'name' => 'count_product',
            'default' => 0,
        ],
        [
            'title' => 'Số lượng đơn hàng',
            'name' => 'count_order',
            'align' => 'right',
            'default' => 0,
        ],
        [
            'title' => 'Trạng thái',
            'default' => 'Active',
        ],
        [
            'title' => 'Thao tác',
            'align' => 'center',
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
            'placeholder' => 'Tìm kiếm tên cửa hàng',
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
                                        <strong>Đồng bộ cửa hàng</strong>
                                    </h6>
                                    <span class="page-subtitle">Danh sách các cửa hàng bên thứ 3 đang kết nối, có thể đồng bộ
                                        tất cả sản
                                        phẩm và đơn hàng vào đây sau khi đã kết nối. Nhấn thêm mới góc bên phải để thêm 1
                                        kết nối mới và
                                        đồng bộ vào hệ thống</span>
                                </div>
                                <div>
                                    @if ($user->permission == 'admin')
                                        <div class="btn btn-danger fs-13 btn-click-remove-js fw-600 pointer">Xoá kết nối
                                        </div>
                                    @endif
                                    <a v-click="{{ $callAjaxModal }}" v-modal-align="center" width="600px"
                                        href="{{ route('site.store.form') }}"
                                        class="btn btn-primary fs-13 fw-600 pointer"><span>Thêm
                                            mới</span></a>
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
        var isConnected = Boolean(parseInt('{{ $request['connected'] ?? '' }}'));
        var shopId = '{{ $request['shopId'] ?? '' }}';
        var url = '{{ route("site.store.form") }}';
        $(function() {
            if (isConnected && shopId) {
                $.ajax({
                        url: `${url}?popup=true&step=3`,
                        type: "GET",
                        dataType: "html",
                    })
                    .done(function(res, status, xhr) {
                        if (xhr.status == 200) {
                            openModal(res, {width: "600px", align: "center", isUpdate: false});
                        }
                    })
                    .fail(function(res) {
                        if (res.status == 401) {
                            window.location.href = window.loginURI + "?callback=" + window.location.href;
                        }
                    });
            }
        });
    </script>
@endsection
