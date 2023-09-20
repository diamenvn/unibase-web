@extends('site.layout.master')
@section('title', 'Danh sách đơn hàng')

@section('content')
<div class="app-content">
    <div class="section">
        <div class="main-body flex flex-column">
            <div class="search-filter bg-white border fs-14">
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-2 row">
                            <div class="col-6">
                                <div class="row">
                                    <div class="form-group m-0 d-flex col-6 fs-13">
                                        <label for="inputEmail3" class="col-form-label">Tìm kiếm</label>
                                        <div class="flex-1 ml-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control fs-14" placeholder="Nhập tên cần tìm" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary fs-14" type="button">Tìm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row justify-content-end">
                                    <div class="col-4 text-right">
                                        <button class="btn btn-info fs-14 btn-create-js" type="button"><i class="fal fa-plus mr-1"></i>Thêm mới</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row flex-1 overflow-auto mt-2">
                <div class="col-12 h-100">
                    <div class="base-table-content base-table-layout flex flex-column">
                        <div class="base-table--data flex-1 overflow-auto">
                            <table id="headerTable" class="table-filter w-100">
                                <thead>
                                    <tr class="sub-header">
                                        <th class="text-center">
                                            <div class="th-container">STT</div>
                                        </th>
                                        <th data-index="0" class="th-typeview-name">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width " style="">Tên API import</span></div>
                                        </th>
                                        <th data-index="1" class="th-typeview-phones">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Link API import</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Trạng thái</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Ngày tạo</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value view-mini">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Thao tác</span></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table-body-data">

                                </tbody>

                            </table>
                        </div>
                        <div class="base-table--paginate">
                            <div class="flex flex-around bg-white paginate">
                                <div class="paginate--block float-right col-6 text-left">
                                    <span>
                                        Số bản ghi / trang
                                        <select class="ml-2" style="height: 30px; width: 50px" name="count_page" id="count_page" class="mx-1">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="200">200</option>
                                        </select>
                                </div>
                                <div id="paginate" class="paginate--block float-right col-6 text-right"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('modal')
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLongTitle">Tạo mới API</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nhập tên <span class="co-red">*</span></label>
                                <input class="form-control bg-white" name="name" type="search" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Sản phẩm</label>
                                <select name="product_id" class="form-control">
                                    @foreach($customer->product as $item)
                                    <option value="{{$item->product->_id}}">{{$item->product->product_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Trạng thái tài khoản</label>
                                <select name="status" class="form-control">
                                    <option value="0">Đang khoá</option>
                                    <option selected value="1">Đang hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" data-id="" class="btn btn-success btn-create-save-js">Tạo ngay</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLongTitle">Chỉnh sửa API nhập đơn hàng</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nhập tên <span class="co-red">*</span></label>
                                <input class="form-control bg-white" name="name" type="search" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Sản phẩm</label>
                                <select name="product_id" class="form-control">
                                    @foreach($customer->product as $item)
                                    <option value="{{$item->product->_id}}">{{$item->product->product_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Trạng thái tài khoản</label>
                                <select name="status" class="form-control">
                                    <option value="0">Đang khoá</option>
                                    <option selected value="1">Đang hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-success btn-update-save-js">Chỉnh sửa</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('custom_js')

<script>
    var stt = 0;
    const api = {
        getOrderList: function(callback) {
            stt = 0;
            lib.send.get('{{route("api.setting.import.api")}}', callback, window.location.search);
        }
    }

    const activity = {
        showDataListOrder: function(res) {
            if (res.success) {
                element.table().html('');
                if (!!res.data.result.data.length) {
                    res.data.result.data.forEach(item => {
                        resHtml = html.renderList(res.data, item);
                        element.table().append(resHtml);
                    });
                } else {
                    element.table().html(html.emptyData());
                }

            }
            Notiflix.Block.Remove('.base-table-content');
            activity.setTotalPage(res.data.result);
            activity.setPaginate(res.data.pagination);
        },
        getData: function() {
            loading.order.show('.base-table-content');
            api.getOrderList(activity.showDataListOrder);
        },
        setForm: function() {
            const params = new URLSearchParams(window.location.search);
            let paramObj = {};
            for (var key of params.keys()) {
                value = params.get(key);

                if (key == "source_id") {
                    if (!!$("[data-source='" + value + "']").length) {
                        $(".list-source--item").removeClass('active');
                        $("[data-source='" + value + "']").addClass('active');
                    }
                } else if (key == "user_id") {
                    $('.filter-user_id-js').val(value);
                } else if (key == "company_mkt_id") {
                    $('.filter-company_mkt_id-js').val(value);
                } else if (key.includes('[]')) {
                    for (var value of params.values()) {
                        id = key.replace('[]', '');
                        $('#' + value).prop('checked', true);
                        $('#' + id + value).prop('checked', true);
                    }
                } else {
                    for (var value of params.values()) {
                        $('#' + key + value).prop('checked', true);
                    }
                }
            }
        },
        setPaginate: function(data) {
            element.paginate().html(data);
        },
        setTotalPage: function(data) {
            $('#all_record').html(data.total);
            $('#count_page').val(data.per_page);

        }
    }

    const html = {
        renderList: function(res, item) {
            stt += 1;
            dataHtml = '';
            var accessLink = '{{ route("api.setting.import.api.push", ":id") }}';
            accessLink = accessLink.replace(':id', item._id);

            var removeLink = '{{route("api.setting.import.remove", ":id")}}';
            removeLink = removeLink.replace(':id', item._id);

            status = "<span class='alert-danger reason-style'>Đã tạm dừng</span>";

            if (item.status == 1) {
                status = "<span class='alert-success reason-style'>Đang hoạt động</span>";
            }
            dataHtml += `<tr data-id="` + item._id + `">
            <td class="td-first text-center">
              ` + stt + `
            </td>
            <td class="cell-hover-border td-typeview-name view-medium">
                <div class="base_field_name-js"><a href="" title="` + item.name + `" class="a_overflow_hidden">` + item.name + `</a></div>
            </td>
            <td class="cell-hover-border td-typeview-phones ">
                <div class="base_field_phones"><span>` + accessLink + `</span></div>
            </td>
            <td class="cell-hover-border td-typeview-phones view-medium">
                ` + status + `
            </td>
            <td class="cell-hover-border td-typeview-phones view-medium">
                <div class="base_field_phones"><span>` + item.created_at + `</span></div>
            </td>
            <td class="td-number text-center view-small">
                <span class="btn-edit-js" data-id="` + item._id + `"><a href="#"><i class="fal fa-edit"></i></a></span>
                <span class="pointer click-remove-ajax mx-1" data-href="` + removeLink + `"><i class="fal fa-trash"></i></span>
            </td>
            
        </tr>`;
            return dataHtml;
        },
        emptyData: function() {
            return '<td class="text-center p-3 bold fs-18" colspan="9">Không có dữ liệu nào</td>';
        }
    }
    $(function() {
        $(document).on('click', '.btn-create-js', function() {
            modal = $('#modalCreate');
            modal.modal();
        });
        $(document).on('click', '.btn-create-save-js', function() {
            modal = $('#modalCreate');
            form = modal.find('form');
            name = form.find('input[name="name"]').val();
            if (!!!name) {
                Notify.show.error('Vui lòng nhập tên API token');
                return
            }
            lib.send.post('{{route("api.setting.import.api.save")}}', function(res) {
                if (res.success) {
                    Notify.show.success(res.msg);
                    modal.modal('hide');
                    activity.getData();
                } else {
                    Notify.show.error(res.msg);
                }
            }, form.serialize());
        });

        $(document).on('click', '.btn-edit-js', function() {
            self = $(this);
            modal = $('#modalUpdate');
            modal.modal();
            form = modal.find('form');
            id = self.data('id');
            request = '{{route("api.setting.import.api.detail", ":id")}}';
            request = request.replace(':id', id);
            params = {
                id: id
            };
            Notiflix.Block.Circle('#modalUpdate .modal-dialog', 'Đang lấy dữ liệu');
            lib.send.get(request, function(res) {
                if (res.success) {
                    form.find('[name="name"]').val(res.data.name);
                    form.find('[name="product_id"]').val(res.data.product_id);
                    form.find('[name="status"]').val(res.data.status);
                    modal.find('.btn-update-save-js').attr('data-id', id);
                    modal.find('.btn-remove-js').attr('data-id', id);
                }
                Notiflix.Block.Remove('#modalUpdate .modal-dialog');
            });
        });

        $(document).on('click', '.btn-update-save-js', function() {
            id = $(this).data('id');
            editApi(id, 'edit');
        });

        window.onclick = function(event) {
            if ($(event.target).hasClass('inp-data-user-reciver-js') || !!$(event.target).parents('.assign-order-js').html()) return;
            $('.assign-order-js').addClass('d-none');
        }

        activity.getData();
        activity.setForm();
    });

    var editApi = function(id, type) {
        if (type == 'edit') {
            request = '{{route("api.setting.import.edit", ":id")}}';
            params = $('#modalUpdate').find('form').serialize();
        }
        
        request = request.replace(':id', id);
        
        lib.send.post(request, function(res) {
            if (res.success) {
                Notify.show.success(res.msg);
                modal.modal('hide');
                activity.getData();
            }else{
                Notify.show.error(res.msg);
            }
            Notiflix.Block.Remove('#modalUpdate .modal-dialog');
        }, params);
    }
</script>
@endsection