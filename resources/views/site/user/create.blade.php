@extends('site.layout.master')
@section('title', 'Tạo mới tài khoản')

@section('content')
<div class="app-content">
    <div class="section">
        <div class="main-body flex flex-column">
            <div class="create-user fs-14 h-100 bg-white">
                <div class="row p-2">
                    <div class="col-md-12 d-flex justify-content-between">
                        <div class="left">
                            <div class="input-group">
                                <input type="text" class="form-control fs-14" placeholder="Tìm kiếm tên tài khoản" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-info fs-14" type="button">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                        <div class="right">
                            <div class="btn btn-success pointer fs-13 mr-2 btn-edit-user-js" data-uid={{$customer->_id}}>Đổi mật khẩu</div>
                            <div class="btn btn-info pointer btn-create-user-js fs-13">Tạo mới tài khoản</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <span class="fs-25">Danh sách tài khoản</span>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="base-table--data flex-1 overflow-auto">
                            <table id="headerTable" class="table-filter w-100 bg-white">
                                <thead>
                                    <tr class="sub-header">
                                        <th>
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;">
                                                STT
                                            </div>
                                        </th>
                                        <th data-index="0" class="th-typeview-name">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Tên tài khoản</span></div>
                                        </th>
                                        <th data-index="1" class="th-typeview-phones">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Họ và tên</span></div>
                                        </th>
                                        <th data-index="3" class="th-typeview-ngay_lead_chuyen_sale">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Số điện thoại</span></div>
                                        </th>
                                        <th data-index="4" class="th-typeview-sale">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Email</span></div>
                                        </th>
                                        <th data-index="5" class="th-typeview-san_pham_quan_tam">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Quản lý</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Loại tài khoản</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Trạng thái</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Ngày tạo</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Chi tiết</span></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table-body-data">
                                    @foreach($users as $key => $user)

                                    <tr data-id="{{$user->_id}}">
                                        <td class="td-first text-center">
                                            {{$key + 1}}
                                        </td>
                                        <td class="cell-hover-border td-typeview-name ">
                                            {{$user->username}}
                                        </td>
                                        <td class="cell-hover-border td-typeview-phones ">
                                            <div class="base_field_phones"><span>{{$user->name}}</span></div>
                                        </td>
                                        <td class="cell-hover-border td-typeview-ngay_lead_chuyen_sale ">
                                            {{$user->phone}}
                                        </td>
                                        <td class="cell-hover-border td-typeview-sale ">
                                            {{$user->email}}
                                        </td>
                                        <td class="pl-3">
                                            @if ($user->permission == "admin" )
                                            <i class="fal fa-check co-green"></i>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="cell-hover-border td-typeview-value bold pl-3">
                                            {{$typeAccount[$user->type_account]}}
                                        </td>
                                        <td class="td-number">
                                            {{$user->active ? 'Đang hoạt động' : 'Đang khoá'}}
                                        </td>
                                        <td class="td-number">
                                            {{$user->created_at}}
                                        </td>
                                        <td class="td-number text-center">
                                            <span class="pointer btn-edit-user-js mx-1" data-uid="{{$user->_id}}"><i class="fal fa-edit"></i></span>
                                            <span class="pointer click-remove-ajax mx-1" data-href="{{route('api.user.remove', $user->_id)}}"><i class="fal fa-trash"></i></span>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('modal')
<div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLongTitle">Chi tiết tài khoản</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                <strong>Lưu ý:</strong> Không chỉnh sửa thông tin nếu không cần thiết
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tên tài khoản <span class="co-red">*</span></label>
                                <input disabled class="form-control bg-white" name="username" type="text" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Họ và tên</label>
                                <input class="form-control" name="name" type="text" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input class="form-control bg-white" name="phone" type="text" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" name="email" type="text" placeholder="" value="">
                            </div>
                        </div>
                        @if ($customer->permission == "admin")
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Loại tài khoản <span class="co-red">*</span></label>
                                    <select name="type_account" id="" class="form-control">
                                        <option value="mkt">Marketing</option>
                                        <option value="sale">Sale</option>
                                        <option value="bill">Vận đơn</option>
                                        <option value="hcns">HCNS - Kế toán</option>
                                        <option value="care">Chăm sóc khách hàng</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Tài khoản quản lý <span class="co-red">*</span></label>
                                    <select name="permission" id="" class="form-control">
                                        <option value="user">Không</option>
                                        <option value="admin">Có</option>
                                    </select>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="type_account" value="">
                            <input type="hidden" name="permission" value="">
                        @endif
                        <div class="col-6">
                            <div class="form-group">
                                <label>Trạng thái tài khoản <span class="co-red">*</span></label>
                                <select name="active" id="" class="form-control">
                                    <option value="0">Đang khoá</option>
                                    <option value="1">Đang hoạt động</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Ngày tạo tài khoản</label>
                                <input disabled class="form-control" name="created_at" type="text" placeholder="" value="">
                            </div>
                        </div>

                    </div>
                    <div class="break"></div>
                    <div class="row">
                        <div class="col-12 my-2 mt-4 text-center">
                            <h6>Thay đổi mật khẩu</h6>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Nhập mật khẩu mới</label>
                                <input class="form-control" name="newpass" type="password" placeholder="" value="">
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Nhập lại mật khẩu</label>
                                <input class="form-control" name="renewpass" type="password" placeholder="" value="">
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" data-id="" class="btn btn-success btn-update-js">Cập nhật</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCreateUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLongTitle">Tạo mới tài khoản</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" name="active" value="1">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info" role="alert">
                                <strong>Lưu ý:</strong> Dòng có dấu <span class="co-red">*</span> là những trường bắt buộc điền
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tên đăng nhập <span class="co-red">*</span></label>
                                <input class="form-control bg-white" name="username" type="text" placeholder="Viết liền không dấu" value="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Họ và tên <span class="co-red">*</span></label>
                                <input class="form-control" name="name" type="text" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Nhập mật khẩu <span class="co-red">*</span></label>
                                <input class="form-control" name="newpass" type="password" placeholder="" value="">
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Nhập lại mật khẩu <span class="co-red">*</span></label>
                                <input class="form-control" name="renewpass" type="password" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input class="form-control bg-white" name="phone" type="text" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" name="email" type="text" placeholder="" value="">
                            </div>
                        </div>
                        @if ($customer->permission == "admin")
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Loại tài khoản</label>
                                    <select name="type_account" id="" class="form-control">
                                        <option value="mkt">Marketing</option>
                                        <option value="sale">Sale</option>
                                        <option value="bill">Vận đơn</option>
                                        <option value="hcns">HCNS - Kế toán</option>
                                        <option value="care">Chăm sóc khách hàng</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Tài khoản quản lý</label>
                                    <select name="permission" class="form-control">
                                        <option value="admin">Có</option>
                                        <option selected value="user">Không</option>
                                    </select>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="permission" value="user">
                            <input type="hidden" name="type_account" value="mkt">
                        @endif
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" data-id="" class="btn btn-success btn-create-js">Tạo mới</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    $(function() {
        modal = $('#modalEditUser');
        modalCreate = $('#modalCreateUser');
        $(document).on('click', '.btn-edit-user-js', function() {
            defaultModal(modal);
            id = $(this).attr('data-uid');
            link = '{{ route("api.user.getDetail", ":id") }}';
            link = link.replace(':id', id);
            elm = modal.find('.modal-dialog');
            Notiflix.Block.Circle('#modalEditUser .modal-dialog', 'Đang lấy dữ liệu');
            modal.find('.btn-update-js').attr('data-uid', id);
            modal.modal();
            lib.send.get(link, function(res) {
                if (res.success) {
                    updateModal(res);
                } else {
                    Notify.show.error(res.msg);
                }
                Notiflix.Block.Remove('#modalEditUser .modal-dialog');
            });
        });

        $(document).on('click', '.btn-update-js', function() {
            form = modal.find('form').serialize();
            hasContinue = true;
            inputsName = ['name'];
            pass = modal.find('input[name="newpass"]');
            repass = modal.find('input[name="renewpass"]');
            $.each(inputsName, function(index, val) {
                item = modal.find('*[name="' + val + '"]');
                if (!!!item.val()) {
                    item.addClass('invalid');
                    hasContinue = false;
                } else {
                    item.removeClass('invalid');
                }
            });
            if (!hasContinue) {
                Notify.show.error('Vui lòng điền đầy đủ thông tin');
                return;
            }
            if (modal.find('*[name="name"]').val().length < 5){
                Notify.show.error('Tên không được ngắn hơn 5 kí tự');
                return;
            }
            if (!!pass.val() || !!repass.val()) {
                if (pass.val() !== repass.val()) {
                    Notify.show.error('Nhập lại mật khẩu không chính xác');
                    pass.addClass('invalid');
                    repass.addClass('invalid');
                    return;
                } else if (pass.val().length < 6 ) {
                    Notify.show.error('Mật khẩu không được ngắn hơn 6 kí tự');
                    pass.addClass('invalid');
                    repass.addClass('invalid');
                    return;
                }else{
                    pass.removeClass('invalid');
                    repass.removeClass('invalid');
                }
            } else {
                pass.removeClass('invalid');
                repass.removeClass('invalid');
            }
            params = form;
            link = '{{ route("api.user.detail.save", ":id") }}';
            link = link.replace(':id', id);
            lib.send.post(link, function(res) {
                if (res.success) {
                    Notify.show.success(res.msg);
                    modal.modal('hide');
                    window.location.reload();
                } else {
                    Notify.show.error(res.msg);
                }
                Notiflix.Block.Remove('#modalEditUser .modal-dialog');
            }, params);

        });

        $(document).on('click', '.btn-create-user-js', function(){
            defaultModal(modalCreate);
            modalCreate.modal();
        });

        $(document).on('click', '.btn-create-js', function(){
            form = modalCreate.find('form').serialize();
            hasContinue = true;
            inputsName = ['username', 'name', 'newpass', 'renewpass'];
            pass = modalCreate.find('input[name="newpass"]');
            repass = modalCreate.find('input[name="renewpass"]');
            $.each(inputsName, function(index, val) {
                item = modalCreate.find('*[name="' + val + '"]');
                if (!!!item.val()) {
                    item.addClass('invalid');
                    hasContinue = false;
                } else {
                    item.removeClass('invalid');
                }
            });
            if (!hasContinue) {
                Notify.show.error('Vui lòng điền đầy đủ thông tin');
                return;
            }
            if (modalCreate.find('*[name="name"]').val().length < 2){
                Notify.show.error('Tên không được ngắn hơn 2 kí tự');
                return;
            }
            if (!!pass.val() || !!repass.val()) {
                if (pass.val() !== repass.val()) {
                    Notify.show.error('Nhập lại mật khẩu không chính xác');
                    pass.addClass('invalid');
                    repass.addClass('invalid');
                    return;
                } else if (pass.val().length < 6 ) {
                    Notify.show.error('Mật khẩu không được ngắn hơn 6 kí tự');
                    pass.addClass('invalid');
                    repass.addClass('invalid');
                    return;
                }else{
                    pass.removeClass('invalid');
                    repass.removeClass('invalid');
                }
            } else {
                pass.removeClass('invalid');
                repass.removeClass('invalid');
            }
            params = form;
            link = '{{ route("api.user.create.save") }}';
            lib.send.post(link, function(res) {
                if (res.success) {
                    Notify.show.success(res.msg);
                    window.location.reload();
                } else {
                    Notify.show.error(res.msg);
                }
                Notiflix.Block.Remove('#modalEditUser .modal-dialog');
            }, params);
        });
    });

    updateModal = function(res) {
        $.each(res.data, function(key, value) {
            modal.find('*[name="' + key + '"]').val(value);
        });
    }

    defaultModal = function(modal) {
        input = modal.find('input');
        $.each(input, function(index, value){
            if ($(value).attr('type') !== "hidden") {
                $(value).val('');
            }
        });
    }
</script>
@endsection