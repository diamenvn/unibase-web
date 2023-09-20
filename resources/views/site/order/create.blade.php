@extends('site.layout.master')
@section('title', 'Danh sách đơn hàng')

@section('content')
<div class="app-content">
    <div class="section">
        <div class="main-body flex flex-column">
            <form action="google.com" method="POST" class="row row--custom h-100 overflow-auto">
                <div class="order-detail--item col-md-8 col-12 h-100 col--custom">
                    <div class="order-detail__title"><i class="fal fa-info-circle mr-1"></i>Tạo mới khách hàng</div>
                    <div class="order-detail__body">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <strong>Lưu ý:</strong> Dòng có dấu <span class="co-red">*</span> là những trường bắt buộc điền!
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Tên khách hàng <span class="co-red">*</span></label>
                                    <input class="form-control bg-white" name="name" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Số điện thoại <span class="co-red">*</span></label>
                                    <input class="form-control bold co-red form-inp-phone-js" onkeypress='number(event)' name="phone" type="text" placeholder="" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Người tạo đơn <span class="co-red">*</span></label>
                                    <select @if($info->permission == "user") disabled @endif name="user_create_id" class="form-control bg-white">
                                        @foreach($info->customer as $customer)
                                        <option @if($customer->_id == $info->_id) selected @endif value="{{$customer->_id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                    @if($info->permission == "user")
                                    <input type="hidden" name="user_create_id" value="{{$info->_id}}">
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Sản phẩm <span class="co-red">*</span></label>
                                    <select name="product_id" class="form-control bg-white">
                                        @foreach($info->product as $product)
                                        <option value="{{$product->product->_id}}">{{$product->product->product_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Công ty <span class="co-red"></span></label>
                                    <input disabled class="form-control bg-white" type="text" placeholder="" value="{{$info->company->company_name}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Nguồn khách hàng <span class="co-red">*</span></label>
                                    <select name="source_id" class="form-control bg-white">
                                        @foreach($info->source as $source)
                                        <option value="{{$source->source->_id}}">{{$source->source->source_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Link bài viết <span class="co-red"></span></label>
                                    <input class="form-control bg-white" name="link" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Địa chỉ khách hàng <span class="co-red"></span></label>
                                    <textarea rows="3" class="form-control bold co-red" name="address" type="text" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Gửi ghi chú tới sale <span class="co-red"></span></label>
                                    <textarea rows="3" class="form-control bold co-red" name="message" type="text" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row--custom">
                        <div class="col--custom col-12">
                            <div class="bg-white d-flex justify-content-center align-items-center w-100 p-2">
                                <div class="btn btn-success mr-2 pointer"><i class="fal fa-save"></i> Lưu dữ liệu</div>
                                <a href="{{route('site.order.list')}}" class="btn btn-info ml-2 pointer btn-back-js"><i class="fal fa-angle-left"></i> Trở về</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-detail--item col-md-4 col-12 h-100 col--custom">
                    <div class="order-detail__title"><i class="fal fa-info-circle mr-1"></i>Tra cứu thông tin khách hàng</div>
                    <div class="order-detail__body">
                        <div class="base-table-content base-table-layout border-top flex flex-column">
                            <div class="base-table--data flex-1 overflow-auto">
                                <table id="headerTable" class="table-filter w-100">
                                    <thead>
                                        <tr class="sub-header">
                                            <th data-index="0" class="th-typeview-name view-small">
                                                <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Tên khách hàng</span></div>
                                            </th>
                                            <th data-index="1" class="th-typeview-phones">
                                                <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Số điện thoại</span></div>
                                            </th>
                                            <th data-index="0" class="th-typeview-value">
                                                <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Sản phẩm</span></div>
                                            </th>
                                            <th data-index="0" class="th-typeview-value view-mini">
                                                <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Ngày tạo</span></div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-body-data">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade" id="modalExistNumber" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLongTitle">Cảnh báo số trùng</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <strong>Số trùng với những khách hàng dưới đây</strong>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="base-table-content base-table-layout border-top flex flex-column">
                                <div class="base-table--data flex-1 overflow-auto">
                                    <table id="headerTable" class="table-filter w-100">
                                        <thead>
                                            <tr class="sub-header">
                                                <th data-index="0" class="th-typeview-name view-small">
                                                    <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Tên khách hàng</span></div>
                                                </th>
                                                <th data-index="1" class="th-typeview-phones">
                                                    <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Số điện thoại</span></div>
                                                </th>
                                                <th data-index="0" class="th-typeview-value">
                                                    <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Sản phẩm</span></div>
                                                </th>
                                                <th data-index="0" class="th-typeview-value view-mini">
                                                    <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Ngày tạo</span></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-body-data">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    var timeout;
    var table = $('.table-body-data');
    var empty = '<td class="text-center p-3 bold fs-18" colspan="4">Không tìm thấy số trùng!</td>';
    $(function() {
        $('.btn-success').click(function() {
            form = $(this).closest('form');
            elementValid = ['name', 'phone', 'user_create_id', 'product_id', 'source_id'];
            hasContinue = true;
            $.each(elementValid, function(index, value) {
                item = $('*[name="' + value + '"]');
                if (!!!item.val()) {
                    item.addClass('invalid');
                    hasContinue = false;
                } else {
                    item.removeClass('invalid');
                }
            });
            if (!hasContinue) {
                Notify.show.error('Vui lòng điền đầy đủ dữ liệu');
                return;
            }

            Notiflix.Loading.Dots('Đang tạo dữ liệu...');
            params = form.serialize();
            lib.send.post('{{route("api.order.create.save")}}', function(res) {
                Notiflix.Loading.Remove();
                if (res.success) {
                    Notify.show.success(res.msg);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    Notify.show.error(res.msg);
                }
            }, params);
        });

        $('.form-inp-phone-js').keyup(function() {
            clearTimeout(timeout);
            val = $(this).val();
            if (!!!val) {
                table.html('');
                return;
            };
            self = $(this);
            params = {
                phone: val
            };
            timeout = setTimeout(function() {
                Notiflix.Loading.Pulse('Đang kiểm tra số trùng...');
                val = val.trim().match(/\d/g).join("");
                self.val(val);
                if (val[0] != "0") {
                    self.val(0 + val);
                }
                lib.send.post('{{route("api.order.searchPhone")}}', function(res) {
                    table.html('');
                    if (res.success) {
                        if (!!res.data.length) {
                            res.data.forEach(item => {
                                table.append(html(item));
                            });
                            $('#modalExistNumber').modal();
                        } else {
                            table.append(empty);
                        }
                    }
                    Notiflix.Loading.Remove();
                }, params);
            }, 1000);
        });
        table.append(empty);
    });

    var html = function(data) {
        response = `<tr>
            <td class="td">` + data.name + `</td>
            <td class="td">` + data.phone + `</td>
            <td class="td">` + data.product.product_name + `</td>
            <td class="td">` + data.created_at + `</td>
        </tr>`;
        return response;
    }
</script>
@endsection