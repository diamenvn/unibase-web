@extends('site.layout.master')
@section('title', 'Thêm mới khách hàng')

@section('content')
<div class="app-content">
    <div class="section">
        <div class="main-body flex flex-column">
            <form action="#" method="POST" class="row row--custom h-100 overflow-auto">
                <div class="order-detail--item col-9 h-100 col--custom">
                    <div class="order-detail__title"><i class="fal fa-info-circle mr-1"></i>Chỉnh sửa thông tin khách hàng</div>
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
                                    <input class="form-control bg-white" name="name" type="text" placeholder="" value="{{$customer->name}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Số điện thoại <span class="co-red">*</span></label>
                                    <input class="form-control bold co-red" readonly onkeypress='number(event)' name="phone" type="text" placeholder="" value="{{$customer->phone}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Email KH <span class="co-red">*</span></label>
                                    <input class="form-control bg-white" readonly name="email" type="text" placeholder="" value="{{$customer->email}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Tên công ty</label>
                                    <input class="form-control bg-white" name="company_name" type="text" placeholder="" value="{{$customer->company_name}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Mã số DN</label>
                                    <input class="form-control bg-white" name="msdn_value" type="text" placeholder="" value="{{$customer->msdn_value}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Mã số thuế</label>
                                    <input class="form-control bg-white" name="mst_value" type="text" placeholder="" value="{{$customer->mst_value}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Nguồn khách hàng <span class="co-red">*</span></label>
                                    <select name="source_id" class="form-control bg-white">
                                        @foreach($info->source as $source)
                                        <option @if($source->_id == $customer->source_id) selected @endif value="{{$source->source->_id}}">{{$source->source->source_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Rank</label>
                                    <input class="form-control bg-white" name="rank" type="text" placeholder="" value="{{$customer->rank}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Ghi chú khách hàng</label>
                                    <textarea rows="3" class="form-control bold" name="note" type="text" placeholder="">{{$customer->note}}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Địa chỉ khách hàng <span class="co-red"></span></label>
                                    <textarea rows="3" class="form-control bold" name="address" type="text" placeholder="">{{$customer->address}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row--custom">
                        <div class="col--custom col-12">
                            <div class="bg-white d-flex justify-content-center align-items-center w-100 p-2">
                                <div class="btn btn-success mr-2 pointer"><i class="fal fa-save"></i> Cập nhật dữ liệu</div>
                                <a href="{{route('site.customer.lists')}}" class="btn btn-info ml-2 pointer btn-back-js"><i class="fal fa-angle-left"></i> Trở về</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-detail--item col-3 h-100">
                    <div class="module bg-white my-2 p-2">
                        <div class="form-group">
                            <label>Ghi chú khách hàng <span class="co-red">*</span></label>
                            <textarea name="activity_value" id="" cols="30" rows="4" class="w-100 form-control" placeholder="Note"></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="btn btn-info btn-send-activity-js btn-sm">Xong</div>
                        </div>
                    </div>

                    <div class="py-2 mb-3">
                        <ol class="activity-feed">
                            @foreach($customer->activity as $activity)
                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <div class="d-block"><span class="name">{{$activity->customer->name}}</span><span class="date">{{$activity->created_at}}</span></div> <span class="activity-text">{!! $activity->activity_value !!}</span>
                                </div>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </form>
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
            elementValid = ['name', 'phone', 'email', 'source_id'];
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

            Notiflix.Loading.Dots('Đang cập nhật dữ liệu...');
            params = form.serialize();
            lib.send.post('{{route("api.customer.update", $customer->_id)}}', function (res) {
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

        $('.btn-send-activity-js').click(function () {
            elementValid = ['activity_value'];
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

            Notiflix.Loading.Dots('Đang cập nhật dữ liệu...');
            params = { activity_value: $('[name="activity_value"]').val() };
            lib.send.post('{{route("api.customer.update_activity", $customer->_id)}}', function (res) {
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
    });
</script>
@endsection