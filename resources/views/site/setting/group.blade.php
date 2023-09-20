@extends('site.layout.master')
@section('title', 'Chia nhóm')

@section('content')
<div class="app-content">
    <div class="section">
        <div class="main-body flex flex-column">
            <div class="row setting-group setting-group--layout h-100">
                <div class="col-4 col--custom setting-group__item d-flex flex-column h-100">
                    <div class="group__title">Danh sách nhóm</div>
                    <div class="group__body flex-1 overflow-x-none overflow-y-auto bg-white">
                        <div class="row p-3">
                            @foreach($listGroups as $group)
                            <div class="col-12 mb-3">
                                <a href="{{route('site.setting.group')}}?group_id={{$group->_id}}" class="d-block card @if($group->_id == $id) active @endif">
                                    <div class="card-body row p-3">
                                        <div class="col-2 bl-avatar">
                                            <img class="w-100 border-radius-circle border-color" src="{{$group->customer->avatar}}" alt="">
                                        </div>
                                        <div class="col-9 bl-information">
                                            <h6 class="m-0 co-green"><strong>{{$group->title}}</strong></h6>
                                            <p class="fs-14 mt-1">{{isset($group->members) ? count($group->members) : 0}} thành viên</p>
                                        </div>
                                        <div class="col-1 fs-20 d-flex align-items-center justify-content-center">
                                            <i class="fal fa-arrow-alt-right"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer border-none">
                                        Được tạo bởi: {{$group->customer->name}} - {{$group->created_at}}
                                    </div>
                                </a>
                            </div>
                            @endforeach
                            <div class="col-12 mb-3">
                                <div class="btn btn-add-group d-block co-white cursor-pointer bg-green" data-toggle="modal" data-target="#modalCreateGroup">Thêm nhóm mới</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8 col--custom setting-group__item d-flex flex-column h-100" id="members">
                    <div class="group__title">Danh sách thành viên trong nhóm</div>
                    <div class="group__body flex-1 overflow-x-none overflow-y-auto bg-white">
                        <div class="row p-3">
                            @if (!empty($members) && !empty($detailGroup))
                            <form id="form-update" class="col-12">
                                <table class="table table__list">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Tên tài khoản</th>
                                            <th>Tên nhân sự</th>
                                            <th class="text-center">Quản lý</th>
                                            <th class="text-center">Xoá</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list-member-js">
                                        @php $key = 0; @endphp
                                        @foreach($members as $item)
                                        @php $key += 1; @endphp
                                        <tr>
                                            <td>{{$key}}</td>
                                            <td>{{$item->username}}</td>
                                            <td>{{$item->name}}</td>
                                            <td class="text-center"><input type="hidden" name="user_id[]" value={{$item->_id}}><input type="checkbox" @if(in_array($item->_id, $detailGroup->leaders)) checked @endif name="leader[]" value="{{$item->_id}}"></td>
                                            <td class="text-center"><i class="fal fa-trash-alt"></i></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <div class="btn btn-info btn-add-member-js"><i class="fal fa-plus mr-1"></i>Thêm nhân sự</div>
                                        <div class="btn btn-success btn-success-js"><i class="fal fa-save mr-1"></i>Lưu dữ liệu</div>
                                    </div>
                                </div>
                            </form>
                            @else
                            <h5 class="px-3">Chọn 1 nhóm hoặc tạo mới ở bên cạnh!</h5>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade" id="modalCreateGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Thêm nhóm mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input type="text" name="group_name" class="form-control" id="input_group_name" aria-describedby="emailHelp" placeholder="Mời nhập tên nhóm cần tạo" require>
                        <div class="invalid-feedback invalid-error">
                            Vui lòng nhập tên nhóm vào đây
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-success btn-create-group-js">Tạo nhóm</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('custom_js')
<script>
    $(function() {
        $('.btn-add-member-js').click(function() {
            html = render.list();
            $('#list-member-js').append(html);
        });

        $(document).on('click', '.fa-trash-alt', function() {
            $(this).closest('tr').remove();
        });

        $('.btn-success-js').click(function() {
            checkLeader = false;
            
            leaders = $('input[name="leader[]"]');
            $.each(leaders, function(index, value) {
                if ($(value).is(':checked')) {
                    checkLeader = true;
                    id = $(value).closest('tr').find('select').val();
                    id = id ? id : $(value).val();
                    $(value).val(id);
                }
            });

            form = $('#form-update').serialize();

            if (!checkLeader) {
                Notify.show.error('Cần phải chọn ít nhất 1 người quản lý nhóm');
                return;
            }
            Notiflix.Loading.Circle('Đang tải dữ liệu...');
            lib.send.post('{{route("api.setting.updateGroup", !empty($detailGroup) ? $detailGroup->_id : 0)}}', function(res) {
                if (res.success){
                    Notify.show.success(res.msg);
                }else{
                    Notify.show.error(res.msg);
                }
                Notiflix.Loading.Remove();
            }, form);
        });

        $('.btn-create-group-js').click(function() {
            input = $('#input_group_name');
            error = $('.invalid-error');
            if (!!!input.val()) {
                input.addClass('invalid');
                error.show();
                return;
            } else {
                input.removeClass('invalid');
                error.hide();
            }
            data = {
                'title': input.val()
            };
            lib.send.post('{{route("api.setting.createGroup")}}', function(res) {
                if (res.success){
                    Notify.show.success(res.msg);
                    window.location.href = res.data.url;
                }else{
                    Notify.show.error(res.msg);
                }
                Notiflix.Loading.Remove();
            }, data);
        });
    })

    var render = {
        list: function() {
            item = '';
            @foreach($listCustomers as $item)
            item += '<option value="{{$item->_id}}">{{$item->username}}</option>';
            @endforeach

            html = `<tr><td>` + parseInt($('#list-member-js tr').length + 1) + `</td>
            <td><select style="width: 200px" name="user_id[]" class="form-control" id="">` + item + `</select></td><td></td>
            <td class="text-center"><input type="checkbox" name="leader[]"></td>
            <td class="text-center"><i class="fal fa-trash-alt"></i></td>
            </tr>`;
            return html;
        }
    }
</script>
@endsection