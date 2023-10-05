@extends('site.layout.master')
@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="app-content">
    <div class="section">
        <div id="app" class="main-body flex flex-column">
            <div class="container-fluid">
                <form class="row">
                    <div class="col-md-9 col-12">
                        <div class="row">
                            <div class="col-12 p-0">
                                <div class="order-detail__header">
                                    <div class="row">
                                        <div class="col">
                                            <div class="order-detail__title d-flex flex-column">
                                                <strong>90950235 - {{$order->name}}</strong>
                                            </div>
                                        </div>
                                        <div class="order-detail__actions col-auto">

                                        </div>
                                    </div>
                                </div>
                                <div class="order-detail__body" id="panel1">
                                    <div class="row">
                                        <div class="col-12">
                                            @if (!empty($order->message))
                                            <div class="alert alert-info" role="alert">
                                                <strong>Ghi chú từ marketing:</strong> {{$order->message}}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            @if (!empty($order->filter_confirm) && $user->permission != "admin")
                                            <div class="alert alert-warning fs-14" role="alert">
                                                <strong>Không thể sửa đơn:</strong> Đơn này đã xác nhận bởi vận đơn nên bạn không thể tiếp tục chỉnh sửa đơn này!
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Tên khách hàng</label>
                                                <input @if ($user->type_account == "sale") readonly @endif class="form-control bg-white" name="name" type="text" placeholder="" value="{{$order->name}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Số điện thoại</label>
                                                <input @if ($user->type_account == "sale") readonly @endif class="form-control bold co-red" name="phone" type="text" placeholder="" value="{{$order->phone}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Công ty</label>
                                                <input readonly class="form-control" type="text" placeholder="" value="{{$order->company->company_name}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Sản phẩm</label>
                                                <select @if ($user->type_account == "sale") disabled @endif class="form-control" name="product_id" id="">
                                                    @foreach($order->companyProduct as $item)
                                                    <option @if($item->product->_id == $order->product_id) selected @endif value="{{$item->product->_id}}">{{$item->product->product_name}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Người tạo đơn</label>
                                                <select @if ($user->type_account == "sale" || $user->permission == "user") disabled @endif name="user_create_id" class="form-control">
                                                    @foreach($order->companyCustomer as $item)
                                                    <option @if($item->_id == $order->user_create_id) selected @endif value="{{$item->_id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Ngày tạo đơn</label>
                                                <input readonly class="form-control" type="text" placeholder="" value="{{$order->created_at}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Mã đơn hàng</label>
                                                <input readonly class="form-control" type="text" placeholder="" value="{{$order->_id}}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Nguồn</label>
                                                <select @if ($user->type_account == "sale") disabled @endif class="form-control" name="source_id" id="">
                                                    @foreach($order->companySource as $item)
                                                    <option @if($item->source->_id == $order->source_id) selected @endif value="{{$item->source->_id}}">{{$item->source->source_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Link bài viết</label>
                                                <input @if ($user->type_account == "sale") readonly @endif class="form-control" name="link" type="text" placeholder="" value="{{$order->link}}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Địa chỉ</label>
                                                <textarea rows="3" @if ($user->type_account == "sale") readonly @endif class="form-control" name="address" type="text" placeholder="">{{$order->address}}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="option-status-order d-flex flex-around overflow-hidden">
                                                    <label for="status-cancel" class="col-4 option__item cancel m-0">
                                                        <span>Huỷ đơn hàng</span>
                                                        <div class="select">
                                                            <input @if ($order->reason == "cancel") checked @endif id="status-cancel" type="radio" value="cancel" name="reason">
                                                        </div>
                                                    </label>
                                                    <label for="status-wait" class="col-4 option__item wait m-0">
                                                        <span>Chưa chốt được</span>
                                                        <div class="select">
                                                            <input @if ($order->reason == "wait" || $order->reason == null) checked @endif id="status-wait" type="radio" value="wait" name="reason">
                                                        </div>
                                                    </label>
                                                    <label for="status-success" class="col-4 option__item success m-0">
                                                        <span>Chốt đơn hàng</span>
                                                        <div class="select">
                                                            <input @if ($order->reason == "success") checked @endif id="status-success" type="radio" value="success" name="reason">
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Tình trạng khách hàng</label>
                                                <select class="form-control" name="filter_status">
                                                    <option value="-1">Chọn tình trạng khách hàng</option>
                                                    @foreach($actions as $action)
                                                    @if ($action->type == "filter_status" || ($action->type == "filter_confirm"))
                                                    <option @if($order->filter_status == $action->_id) selected @endif value="{{$action->_id}}">{{$action->text}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Note tình trạng đơn hàng</label>
                                                <textarea rows="3" class="form-control" type="text" name="note" placeholder="Nhập nội dung ghi chú đơn..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="bg-transparent">
                            123123123
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
        $('.btn-add-product-ship').click(function () {
            panel = $('#area-product');
            html = `<tr id="ship-order">
                                                <td class="relative">
                                                    <select name="ship-product[]" class="form-control changeProduct" id="">
                                                        @foreach($order->companyProduct as $item)
                                                            <option @if($item->product->_id == $order->product_id) selected @endif value="{{$item->product->_id}}">{{$item->product->product_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input name="ship-amount[]" class="form-control amount" data-type="currency" onkeypress='number(event)' type="text" value="1" placeholder="Số lượng"></td>
                                                <td><input name="ship-price[]"data-type="currency" data-type="currency" onkeypress='number(event)' class="form-control price" type="text" placeholder="Thành tiền" value="{{number_format($order->product->price)}}"></td>
                                                <td class="text-center"><i class="fas fa-trash-alt"></i></td>
                                            </tr>`;
            panel.append(html);
            sum();
            $("input[data-type='currency']").simpleMoneyFormat();
        });

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