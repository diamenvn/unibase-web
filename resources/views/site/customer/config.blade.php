@php(
    $columns = [
        [
            'title' => 'Mã khách hàng',
        ],
        [
            'title' => 'Tên khách hàng',
        ],
        [
            'title' => 'Email khách hàng',
        ],
        [
            'title' => 'Số điện thoại',
        ],
        [
            'title' => 'Số lượng đơn đã đặt',
        ],
        [
            'title' => 'Số lượng đơn đã hoàn thành',
        ],
        [
            'title' => 'Số tiền đã thanh toán',
        ],
        [
            'title' => 'Thao tác',
        ],
    ]
)

@php(
    $dataTable = [
        'columns' => $columns,
        'config' => [
            'checkboxAll' => true
        ]
    ]
)

@php
    $hi = 234234;
@endphp