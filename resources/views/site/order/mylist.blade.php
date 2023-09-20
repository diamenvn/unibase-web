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
                            <div class="form-group m-0 col-2 fs-13 row break-right">
                                <label for="inputEmail3" class="col-form-label">Thời gian</label>
                                <div class="flex-1 ml-3">
                                    <input type="text" timepicker="true" readonly name="updated_at" class="fs-13 form-control pointer" placeholder="Lựa chọn thời gian">
                                </div>
                            </div>
                            <div class="form-group m-0 col-2 fs-13 row break-right">
                                <label for="inputEmail3" class="col-form-label">Sản phẩm</label>
                                <div class="flex-1 ml-3 position-relative">
                                    <input type="text" readonly class="fs-13 form-control pointer" name="product_id" data-toggle="menu" data-target="#menu-product" placeholder="Chọn sản phẩm">
                                    <ul class="list bg-white" id="menu-product">
                                        @foreach($product as $item)
                                        <li value="{{$item->_id}}" text="{{$item->product_name}}" class="d-flex justify-content-between list-item-js">
                                            <span class="name">{{$item->product_name}}</span>
                                            <span class="icon-check co-green"><i class="fal fa-check"></i></span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>


                            <div class="form-group m-0 col-2 fs-13 row">
                                <label for="inputEmail3" class="col-form-label">Trạng thái</label>
                                <div class="flex-1 ml-3 position-relative">
                                    <input type="text" readonly class="fs-13 form-control pointer" name="reason" data-toggle="menu" data-target="#menu-reason" placeholder="Chọn trạng thái">
                                    <ul class="list bg-white" id="menu-reason">
                                        <li value="-1" text="Tất cả" data-selected="true" class="d-flex justify-content-between list-item-js">
                                            <span class="name">Tất cả</span>
                                            <span class="icon-check co-green"><i class="fal fa-check"></i></span>
                                        </li>
                                        <li value="success" text="Đơn đã chốt" class="d-flex justify-content-between list-item-js">
                                            <span class="name">Đơn đã chốt</span>
                                            <span class="icon-check co-green"><i class="fal fa-check"></i></span>
                                        </li>
                                        <li value="wait" text="Chưa chốt được" class="d-flex justify-content-between list-item-js">
                                            <span class="name">Chưa chốt được</span>
                                            <span class="icon-check co-green"><i class="fal fa-check"></i></span>
                                        </li>
                                        <li value="cancel" text="Đơn huỷ" class="d-flex justify-content-between list-item-js">
                                            <span class="name">Đơn huỷ</span>
                                            <span class="icon-check co-green"><i class="fal fa-check"></i></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row flex-1 overflow-auto mt-2">
                <div class="col-12 h-100">
                    <div class="base-table-content base-table-layout border-top flex flex-column">
                        <div class="base-table--filter border-0">
                            <div class="panel-heading-left panel-heading-title" id="panel-heading-title">
                                <h6 class="panel-title mb-1">
                                    <strong>Danh sách khách hàng</strong>
                                </h6>
                                (Tìm thấy : <span id="all_record">0</span> Khách hàng)
                            </div>
                            <div class="panel-heading-left d-flex align-items-center form-group m-0 search h-100">
                                <form class="input-group" onsubmit="return false">
                                    <input type="search" name="search" class="form-control fs-14 view-large inp-find-item-order-js" placeholder="Tìm số điện thoại, tên khách hàng" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <input class="btn btn-primary fs-14 btn-click-find-order-js" type="submit" value="Tìm kiếm" />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="base-table--data flex-1 overflow-auto">
                            <table id="headerTable" class="table-filter w-100">
                                <thead>
                                    <tr class="sub-header">
                                        <th class="text-center">
                                            <div class="th-container">STT</div>
                                        </th>
                                        <th data-index="0" class="th-typeview-name">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Tên khách hàng</span></div>
                                        </th>
                                        <th data-index="1" class="th-typeview-phones">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Số điện thoại</span></div>
                                        </th>
                                        <th data-index="4" class="th-typeview-sale view-large">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Ghi chú</span></div>
                                        </th>
                                        <th data-index="5" class="th-typeview-san_pham_quan_tam">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Sản phẩm quan tâm</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Trạng thái</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Tình trạng khách hàng</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Ngày cập nhật</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value view-mini">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Chi tiết</span></div>
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


@section('custom_js')

<script>
    var stt = 0;
    const api = {
        getOrderList: function(callback) {
            stt = 0;
            lib.send.get('{{route("api.order.getAllListMyOrder")}}', callback, window.location.search);
        }
    }

    const activity = {
        showDataListOrder: function(res) {
            if (res.success) {
                element.table().html('');
                if (!!res.data.result.data) {
                    for (var key in res.data.result.data) {
                        resHtml = html.renderList(res.data, res.data.result.data[key]);
                        element.table().append(resHtml);
                    }
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
            if (!!!window.location.search.includes('updated_at')) {
                now = "{{date('Y-m-d')}}";
                $('[name="updated_at"]').val(now);
                lib.updateParams('updated_at', now);
            }
            for (var key of params.keys()) {
                value = params.get(key);

                if (key == "source_id") {
                    if (!!$("[data-source='" + value + "']").length) {
                        $(".list-source--item").removeClass('active');
                        $("[data-source='" + value + "']").addClass('active');
                    }
                } else if (key == "user_id") {
                    $('.filter-user_id-js').val(value);
                } else if (key == "updated_at" || key == "search") {
                    $('[name="' + key + '"]').val(value);
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
                        // $('#' + key + value).prop('checked', true);

                        lib.setValueToggle(key, value);
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
            dataHtml = date = note = filterStatus = '';
            var detail = '{{ route("site.order.detail", ":id") }}';
            detail = detail.replace(':id', item.order._id);
            reason = {
                'wait': "<span class='alert-warning reason-style'>Chưa chốt được</span>",
                'success': "<span class='alert-success reason-style'>Đơn đã chốt</span>",
                'cancel': "<span class='alert-danger reason-style'>Đơn huỷ</span>",
                'undefined': "<span class='alert-primary reason-style'>Đơn hàng mới</span>",
                'null': "<span class='alert-warning reason-style'>Chưa chốt được</span>"
            }
            if (!!!item.product_id) {
                return;
            }

            if (!!item.origin_note) {
                note = item.origin_note;
            }
            if (!!item.order.filter_status) {
                filterStatus = item.order.filter_status.text;
            }

            dataHtml += `<tr data-id="` + item._id + `">
            <td class="td-first text-center">
              ` + stt + `
            </td>
            <td class="cell-hover-border td-typeview-name view-medium">
                <div class="base_field_name-js white-space-normal "><a href="` + detail + `" title="` + item.order.name + `" class="detail-js a_overflow_hidden">` + item.order.name + `</a></div>
            </td>
            <td class="cell-hover-border td-typeview-phones ">
                <div class="base_field_phones"><span>` + item.order.phone + `</span></div>
            </td>
            <td class="cell-hover-border td-typeview-phones view-medium white-space-normal">
                ` + note + `
            </td>
            <td class="cell-hover-border td-typeview-phones view-medium">
                ` + res.product[item.product_id].product_name + `
            </td>
            <td class="cell-hover-border td-typeview-phones view-medium">
                <div class="base_field_phones"><span>` + reason[item.reason] + `</span></div>
            </td>
            <td class="cell-hover-border td-typeview-phones view-medium">
                ` + filterStatus + `
            </td>
            <td class="cell-hover-border td-typeview-phones view-medium">
                <div class="base_field_phones"><span>` + item.updated_at + `</span></div>
            </td>
            <td class="td-number text-center">
              <span class="btn-edit-js"><a href="` + detail + `"><i class="fal fa-edit"></i></a></span>
            </td>
            
        </tr>`;
            return dataHtml;
        },
        emptyData: function() {
            return '<td class="text-center p-3 bold fs-18" colspan="10">Không có dữ liệu nào</td>';
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

        $(document).on('click', '.btn-remove-js', function() {
            id = $(this).data('id');
            editApi(id, 'remove');
        });

        $(document).on('click', '.list-menu-dropdown li', function() {
            setTimeout(function() {
                activity.getData();
            }, 0)
        });

        $('#count_page').change(function() {
            lib.updateParams('limit', $(this).val());
            activity.getData();
        });

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();

            $('.pagination li').removeClass('active');
            $(this).parent('li').addClass('active');

            var myurl = $(this).attr('href');
            var page = $(this).attr('href').split('page=')[1];

            lib.updateParams('page', page);
            activity.getData();
        });

        $('[timepicker="true"]').daterangepicker({
            singleDatePicker: true,
            autoUpdateInput: false
        }, function(start, end, label) {
            name = $(this)[0].element[0].name;
            time = start.format('Y-MM-DD');
            $('[name="' + name + '"]').val(time);
            lib.updateParams(name, time);
            activity.getData();
        });

        $(document).on('dblclick', '.base-table--data tbody tr', function() {
            window.open($(this).find('.detail-js').attr('href'), '_blank');
        });

        $(document).on('click', '.btn-click-find-order-js', function() {
            inp = $('.inp-find-item-order-js').val();
            if (!!!inp) {
                lib.removeParams('search');
                activity.setForm();
            } else {
                $('input[type="checkbox"]').prop('checked', false);
                $('select').prop("selectedIndex", 0);
                lib.removeAllParams();
                lib.updateParams('search', inp);
            }

            activity.getData();
        });

        window.onclick = function(event) {
            if ($(event.target).hasClass('inp-data-user-reciver-js') || !!$(event.target).parents('.assign-order-js').html()) return;
            $('.assign-order-js').addClass('d-none');
        }

        activity.setForm();
        activity.getData();

    });

    var editApi = function(id, type) {
        if (type == 'edit') {
            request = '{{route("api.setting.import.edit", ":id")}}';
            params = $('#modalUpdate').find('form').serialize();
        } else if (type == 'remove') {
            request = '{{route("api.setting.import.remove", ":id")}}';
            params = [];
        }

        request = request.replace(':id', id);

        lib.send.post(request, function(res) {
            if (res.success) {
                Notify.show.success(res.msg);
                modal.modal('hide');
                activity.getData();
            } else {
                Notify.show.error(res.msg);
            }
            Notiflix.Block.Remove('#modalUpdate .modal-dialog');
        }, params);
    }
</script>
@endsection