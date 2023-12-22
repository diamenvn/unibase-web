@extends('site.layout.master')
@section('title', 'Chi tiết đơn hàng')

@section('content')
@php $indexActive = -1 @endphp
@foreach($steps as $index => $step)
@if ($step->_id == $order->label_id)
@php $indexActive = $index; @endphp
@endif
@endforeach
<div class="app-content">
    <div class="section p-0">
        <div id="app" class="main-body flex flex-column">
            <div class="container-fluid">
                <form class="row">
                    <div class="col-md-9 col-12 order-detail__body pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex order-detail__title px-0">
                                    <div class="d-flex flex-1">
                                        <a href="{{$urlBack}}" class="back-button-wrapper d-flex justify-content-center align-items-center px-3 mr-2">
                                            <i class="fal fa-chevron-left fs-20 fw-600"></i>
                                        </a>
                                        <div class="flex-column d-flex">
                                            <div>#{{$order->order_number ?? '202300113'}} - {{$order->customer_1->name}} - <span class="status-payment {{$order->paid_order == 'paid' ? 'status-payment-paid' : 'status-payment-pending'}}">{{$order->paid_order == 'paid' ? 'Đã thanh toán' : 'Chưa
                                                    thanh toán'}}</span></div>
                                            <span class="co-default fs-12 text-tranform-none fw-400">Ngày tạo: {{$order->created_at}}</span>
                                        </div>
                                    </div>
                                    <div class="order-detail__actions d-flex align-items-center">
                                        <div class="btn btn-success mr-2 pointer text-tranform-none btn-next-step-js fs-14">Chuyển tiếp</div>
                                        <div class="btn btn-danger mr-2 pointer text-tranform-none btn-cancel-step-js fs-14">Đánh dấu thất bại</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="break-horizontal my-2"></div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-column co-default fs-14">
                                    <div class="w-100 mb-1">
                                        <span class="ml-2 mr-3"><i class="fas fa-info-circle" style="width: 20px;"></i></span><span>Mã đơn hàng: #{{$order->order_number}}, trạng thái hiện tại: <strong class="co-green">{{$steps[$indexActive]->label_name}}</strong></span>
                                    </div>
                                    <div class="w-100 mb-1 d-flex">
                                        <span class="ml-2 mr-3"><i class="fas fa-tags" style="width: 20px;"></i></span>
                                        <span class="badgets d-flex">
                                            @foreach($order->order_type as $badget)
                                            <div class="badget badget--danger mr-2">{{$badget}}</div>
                                            @endforeach
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="wrapper-arrow-steps">
                                    <div class="arrow-steps clearfix my-3">
                                        @foreach($steps as $index => $step)
                                        @if ($step->step_type == "cancel") @php continue; @endphp @endif
                                        @if ($index < $indexActive) <div class="step"> <span>{{$step->label_name}}</span>
                                    </div>
                                    @elseif ($index == $indexActive)
                                    <div class="step active fw-600"> <span>{{$step->label_name}}</span> </div>
                                    @else

                                    <div class="step step-default"> <span>{{$step->label_name}}</span> </div>
                                    @endif
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="break-horizontal mb-3"></div>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="form-group">
                                <label class="text-transform-uppercase">Mô tả</label>
                                <textarea class="form-control" name="description" type="text" rows="5" placeholder="Mô tả">{{$order->note}}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="text-transform-uppercase">Trường dữ liệu tùy chỉnh</label>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="jumbotron py-0 px-2 mb-2 fs-14" style="background: #f3f3f3;">
                                <div class="row">
                                    <div class="col-12 mx-2">
                                        <ul class="p-3 list-style-auto">
                                            <li class="mb-3">
                                                <div>Tên khách hàng</div>
                                                <div class="mt-1"><strong>{{$order->customer_1->name}}</strong></div>
                                            </li>
                                            <li class="mb-3">
                                                <div>Số điện thoại</div>
                                                <div class="mt-1"><strong class="co-red">{{$order->customer_1->phone}}</strong></div>
                                            </li>
                                            <li class="mb-3">
                                                <div>Tổng số tiền cần thanh toán</div>
                                                <div class="mt-1"><strong class="co-green">{{$order->total_pending_price ?? "200.000.000đ"}}</strong></div>
                                            </li>
                                            <li class="mb-3">
                                                <div>Tổng giá trị đơn hàng</div>
                                                <div class="mt-1"><strong class="co-green">{{$order->total_price ?? "200.000.000đ"}}</strong></div>
                                            </li>
                                            <li class="mb-3">
                                                <div>Link file đơn hàng</div>
                                                <div class="mt-1"><strong>{{$order->link_order_file ?? ""}}</strong></div>
                                            </li>
                                            <li class="mb-3">
                                                <div>Link file thiết kế</div>
                                                <div class="mt-1"><strong>{{$order->link_order_design ?? ""}}</strong></div>
                                            </li>
                                            <li class="mb-3">
                                                <div>Ngày tạo đơn hàng</div>
                                                <div class="mt-1"><strong>{{$order->created_at}}</strong></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="text-transform-uppercase">Danh sách sản phẩm</label>
                            </div>
                            <div class="base-table--data flex-1 overflow-auto">
                                <table class="table-filter w-100">
                                    <thead>
                                        <tr class="merge">
                                            <th class="text-right">STT</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Mã SKU</th>
                                            <th>Số lượng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-right">1</td>
                                            <td>Ốp điện thoại hình mèo</td>
                                            <td>SKU-002-003</td>
                                            <td>500</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right">1</td>
                                            <td>Ốp điện thoại hình mèo</td>
                                            <td>SKU-002-003</td>
                                            <td>500</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div> --}}
                    </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="bg-transparent my-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="module module-order-current-status p-3 mb-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="text-default-white fs-12 mb-2">GIAI ĐOẠN HIỆN TẠI</div>
                                    </div>
                                    <div class="col-12">
                                        <strong class="fs-20">[{{$indexActive}}/{{count($steps)}}] - {{$steps[$indexActive]->label_name}}</strong>
                                        <div class="d-flex mt-2">
                                            <div class="text-default-white fs-12">Thời hạn: 15:56 06/10/2023</div>
                                        </div>
                                        <div class="progress progress--white my-2">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:60%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <div class="text-default-white fs-12">Kỳ vọng: 5h</div>
                                            <div class="text-default-white fs-12">Đã sử dụng: 3.5h</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="module module-order-info px-3 py-2 mb-3">
                                <div class="title">Thông tin đơn hàng</div>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-between align-items-center fs-14 my-1">
                                        <div><i class="fal fa-info-circle" style="width: 25px;"></i>Mã đơn hàng</div>
                                        <strong class="fs-12">#{{$order->order_number}}</strong>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between align-items-center fs-14 my-1">
                                        <div><i class="fal fa-user" style="width: 25px;"></i>Người tạo đơn</div>
                                        <strong class="fs-12">{{$order->customerCreateOrder->name}}</strong>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between align-items-center fs-14 my-1">
                                        <div><i class="fal fa-clock" style="width: 25px;"></i>Last update</div>
                                        <strong class="fs-12">{{$order->updated_at}}</strong>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between align-items-center fs-14 my-1">
                                        <div><i class="fal fa-tags" style="width: 25px;"></i>Giai đoạn hiện tại</div>
                                        <strong class="fs-12">{{$steps[$indexActive]->label_name}}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="py-2 mb-3">
                                <ol class="activity-feed">
                                    @foreach($order->activity as $activity)
                                    <li class="feed-item">
                                        <div class="feed-item-list">
                                            <div class="d-block"><span class="name">{{$activity->customer->name}}</span><span class="date">{{$activity->created_at}}</span></div> <span class="activity-text">{!! $activity->note !!}</span>
                                        </div>
                                    </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

@endsection


@section('custom_js')
<script>
    product = [];
    filterConfirm = '{{$order->filter_confirm}}';
    permission = '{{$user->permission}}';
    @foreach($order -> companyProduct as $item)
    product.push({
        'id': "{{$item->product->_id}}",
        'price': "{{number_format($item->product->price)}}"
    });
    @endforeach

    $(function () {
        $('.btn-next-step-js').click(function () {
            Notiflix.Loading.Dots('Đang lưu dữ liệu...');
            lib.send.post('{{route("api.order.next-step", $order->_id)}}', function (res) {
                Notiflix.Loading.Remove();
                if (res.success) {
                    Notify.show.success('Thành công');
                    window.location.href = '{!! $urlBack !!}';
                } else {
                    Notify.show.error('Lưu dữ liệu thất bại!');
                }
            });
        });

        $('.btn-cancel-step-js').click(function () {
            Notiflix.Loading.Dots('Đang lưu dữ liệu...');
            lib.send.post('{{route("api.order.cancel-step", $order->_id)}}', function (res) {
                Notiflix.Loading.Remove();
                if (res.success) {
                    Notify.show.success('Thành công');
                    window.location.href = '{!! $urlBack !!}';
                } else {
                    Notify.show.error('Lưu dữ liệu thất bại!');
                }
            });
        });

        if (!!filterConfirm && permission != 'admin') {
            input = $('input');
            select = $('select');
            textarea = $('textarea');

            $.each(input, function (index, element) {
                $(element).attr('disabled', true);
            });

            $.each(select, function (index, element) {
                $(element).attr('disabled', true);
            });

            $.each(textarea, function (index, element) {
                $(element).attr('disabled', true);
            });
        }
        $(document).on('click', '.fa-trash-alt', function () {
            self = $(this);
            self.closest('tr').remove();
            sum();
        });

        $(document).on('change', '.changeProduct', function () {
            self = $(this);
            product.forEach(res => {
                if (res.id == self.val()) {
                    self.closest('tr').find('.price').val(res.price);
                }
            });
            sum();
        });

        $(document).on('keyup', '.amount, .price, .discount, .transport, .charge', function () {
            sum();
        });

        $('.btn-success').click(function () {
            if (!!filterConfirm && permission != 'admin') {
                Notify.show.error('Đơn đã xác nhận bởi kho không thể chỉnh sửa');
                return;
            }

            inputsName = ['name', 'phone', 'reason'];
            filterStatus = $('[name="filter_status"]');
            @if ($user -> type_account == "sale" && $user -> permission == "user")
                inputsName.push('note');
            @endif
            hasContinue = true;
            $.each(inputsName, function (index, val) {
                item = $('*[name="' + val + '"]');
                if (!!!item.val()) {
                    item.addClass('invalid');
                    hasContinue = false;
                    $('#panel1').animate({
                        scrollTop: parseInt(item.offset().top)
                    }, 700);
                } else {
                    item.removeClass('invalid');
                }
            });
            @if ($user -> type_account == "sale" && $user -> permission == "user")
                if (filterStatus.val() == -1) {
                    filterStatus.addClass('invalid');
                    Notify.show.error('Vui lòng chọn tình trạng đơn hàng');
                    $('#panel1').animate({
                        scrollTop: parseInt(filterStatus.offset().top)
                    }, 700);
                    return;
                } else {
                    filterStatus.removeClass('invalid');
                }
            @endif
            if (!hasContinue) {
                Notify.show.error('Vui lòng điền đầy đủ vào ô đánh dấu màu đỏ');
                return;
            }
            hasContinue = true;
            if ($('input[name="reason"]:checked').val() == "success") {
                $.each($('input[name="ship-amount[]"]'), function (index, val) {
                    item = $(val);
                    if (!!!item.val()) {
                        hasContinue = false;
                    }
                });
            }

            if (!hasContinue) {
                Notify.show.error('Đơn đã chốt vui lòng điền thông tin người nhận hàng!');
                return;
            }
            Notiflix.Loading.Dots('Đang lưu dữ liệu...');
            params = $('form').serialize();
            lib.send.post('{{route("api.order.detail.save", $order->_id)}}', function (res) {
                Notiflix.Loading.Remove();
                if (res.success) {
                    Notify.show.success('Thành công');
                    window.location.href = '{!! $urlBack !!}';
                } else {
                    Notify.show.error('Lưu dữ liệu thất bại!');
                }
            }, params);
        });

        $('[name="ship-provin"]').click(function () {
            Notiflix.Block.Dots('.list--filter-search-provin');
            $('.list--filter-search').addClass('d-none');
            $('.list--filter-search-provin').removeClass('d-none');
            lists = $('.list--filter-search-provin ul');
            lists.html('');
            lib.send.get('{{route("api.info.getProvin")}}', function (res) {
                if (res.success) {
                    $.each(res.data, function (index, val) {
                        lists.append('<li class="select-data-provin" data-provin="' + val.id + '">' + val.name + '</li>');
                    });
                }
                Notiflix.Block.Remove('.list--filter-search-provin');
            });
        });

        $(document).on('click', '.select-data-provin', function () {
            self = $(this);
            self.closest('.form-group').find('input').val(self.html());
            lists = $('.list--filter-search-district ul');
            lists.html('');
            lib.send.get('{{route("api.info.getDistrict")}}', function (res) {
                if (res.success) {
                    $.each(res.data, function (index, val) {
                        lists.append('<li class="select-data-district" data-district="' + val.id + '">' + val.name + '</li>');
                    });
                    $('[name="ship-district"]').click();
                }
            }, '?ID=' + self.data('provin'));
            $('[name="ship-town"]').val('');
            $('[name="ship-district"]').val('');
        });

        $('[name="ship-district"]').click(function () {
            $('.list--filter-search').addClass('d-none');
            $(this).closest('.form-group').find('.list--filter-search').removeClass('d-none');
        });

        $(document).on('click', '.select-data-district', function () {
            self = $(this);
            self.closest('.form-group').find('input').val(self.html());
            lists = $('.list--filter-search-town ul');
            lists.html('');
            lib.send.get('{{route("api.info.getTown")}}', function (res) {
                if (res.success) {
                    $.each(res.data, function (index, val) {
                        lists.append('<li class="select-data-town">' + val.prefix + ' ' + val.name + '</li>');
                    });
                    $('[name="ship-town"]').click();
                }
            }, '?ID=' + self.data('district'));
            $('[name="ship-town"]').val('');
        });

        $('[name="ship-town"]').click(function () {
            $('.list--filter-search').addClass('d-none');
            $(this).closest('.form-group').find('.list--filter-search').removeClass('d-none');
        });

        $(document).on('click', '.select-data-town', function () {
            self = $(this);
            self.closest('.form-group').find('input').val(self.html());
        });

        $('.btn-click-remove-js').click(function () {
            self = $(this);
            id = self.data('id');
            Notify.show.confirm(function () {
                params = { _id: id };
                lib.send.post('{{route("api.order.removeOrder")}}', function (res) {
                    if (res.success) {
                        Notify.show.success(res.msg);
                        setTimeout(function () {
                            window.location.href = '{!! $urlBack !!}';
                        }, 1000);
                    } else {
                        Notify.show.error(res.msg);
                    }
                }, params);
            })
        });

        $(document).on('keyup', '.open-submenu', function () {
            searchData($(this));
        });

        window.onclick = function (event) {
            if ($(event.target).hasClass('open-submenu')) return;
            $('.list--filter-search').addClass('d-none');
        }
    });

    var sum = function () {
        products = $('#area-product').find('tr');
        total = 0;
        $.each(products, function (index, value) {
            amount = $(value).find('.amount').val().replace(/[^0-9\.]+/g, "") || 0;
            price = $(value).find('.price').val().replace(/[^0-9\.]+/g, "") || 0;
            total += parseInt(amount) * parseInt(price);
        });
        discount = $('.discount').val().replace(/[^0-9\.]+/g, "") || 0;
        transport = $('.transport').val().replace(/[^0-9\.]+/g, "") || 0;
        charge = $('.charge').val().replace(/[^0-9\.]+/g, "") || 0;
        total += (parseInt(transport) + parseInt(charge)) - parseInt(discount);
        $('.total').val(total);
        $('.total').simpleMoneyFormat();
    }
</script>
@endsection