@extends('site.layout.master')
@section('title', 'Tạo mới đơn hàng')

@section('content')
<div class="app-content">
    <div class="section p-0">
        <div class="main-body flex flex-column">
            <form action="#" method="POST" class="row row--custom h-100 overflow-auto">
                <div class="order-detail--item col-md-12 col-12 h-100 col--custom">
                    <div class="order-detail__title"><i class="fal fa-info-circle mr-1"></i>Tạo mới đơn hàng</div>
                    <div class="order-detail__body">
                        <div class="row">
                            <div class="col-6">
                                @include('site.uikit.input.text', ['label' => "Tên khách hàng", 'model' => "customer_name", 'require' => true])
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Số điện thoại khách hàng</label>
                                    <input disabled class="form-control bold co-red" onkeypress='number(event)' name="phone" type="text" placeholder="" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Người tạo đơn <span class="co-red">*</span></label>
                                    <input disabled class="form-control bold" disabled name="name" type="text" placeholder="" value="{{$user->name}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Nhãn đơn hàng</label>
                                    <div class="d-flex">
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" value="">Quan trọng
                                            </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" value="">Hỏa tốc
                                            </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" value="">Cần xử lý ngay
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                @include('site.uikit.input.currency', ['label' => "Tổng số tiền cần thanh toán", 'model' => "total_pending_price"])
                            </div>
                            <div class="col-6">
                                @include('site.uikit.input.currency', ['label' => "Tổng giá trị đơn hàng", 'model' => "total_price"])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Ghi chú</label>
                                    <textarea rows="2" class="form-control" name="note" type="text" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Link file đơn hàng</label>
                                    <textarea rows="2" class="form-control" name="link_order_file" type="text" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Link file TK</label>
                                    <textarea rows="2" class="form-control" name="link_order_design" type="text" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Chọn sản phẩm</label>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row--custom">
                        <div class="col--custom col-12">
                            <div class="bg-white d-flex justify-content-center align-items-center w-100 p-2">
                                <div class="btn btn-success mr-2 pointer"><i class="fal fa-save"></i> Lưu dữ liệu</div>
                                <a href="{{route('site.order.lists')}}" class="btn btn-info ml-2 pointer btn-back-js"><i class="fal fa-angle-left"></i> Trở về</a>
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
    $(function () {
        $('.btn-success').click(function () {
            form = $(this).closest('form');
            elementValid = ['name', 'phone', 'user_create_id', 'product_id', 'source_id'];
            hasContinue = true;
            $.each(elementValid, function (index, value) {
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
            lib.send.post('{{route("api.order.create.save")}}', function (res) {
                Notiflix.Loading.Remove();
                if (res.success) {
                    Notify.show.success(res.msg);
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else {
                    Notify.show.error(res.msg);
                }
            }, params);
        });

        $('.form-inp-phone-js').keyup(function () {
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
            timeout = setTimeout(function () {
                Notiflix.Loading.Pulse('Đang kiểm tra số trùng...');
                val = val.trim().match(/\d/g).join("");
                self.val(val);
                if (val[0] != "0") {
                    self.val(0 + val);
                }
                lib.send.post('{{route("api.order.searchPhone")}}', function (res) {
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

    var html = function (data) {
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