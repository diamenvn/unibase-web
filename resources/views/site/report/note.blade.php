@extends('site.layout.master')
@section('title', 'Báo cáo doanh thu')

@section('content')
<div class="app-content">
    <div class="section">
        <div class="main-body flex flex-column">
            <div class="search-filter bg-white border fs-14">
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-2 row">
                            <div class="form-group m-0 col-2 fs-13 row">
                                <label for="inputEmail3" class="col-form-label">Từ ngày</label>
                                <div class="flex-1 ml-3">
                                    <input type="text" timepicker="true" readonly name="time_start" class="fs-13 form-control pointer" placeholder="Ngày bắt đầu">
                                </div>
                            </div>
                            <div class="form-group m-0 col-2 fs-13 row break-right">
                                <label for="inputEmail3" class="col-form-label">Đến ngày</label>
                                <div class="flex-1 ml-3">
                                    <input type="text" timepicker="true" readonly name="time_end" class="fs-13 form-control pointer" placeholder="Ngày kết thúc">
                                </div>
                            </div>

                            <div class="form-group m-0 col-2 fs-13 row break-right">
                                <label for="inputEmail3" class="col-form-label">Tài khoản</label>
                                <div class="flex-1 ml-3 position-relative">
                                    <input type="text" readonly class="fs-13 form-control pointer" name="user_create_id" data-toggle="menu" data-target="#menu-account" placeholder="Chọn tài khoản">
                                    <ul class="list bg-white" id="menu-account">
                                        <li value="-1" data-selected="true" text="Tất cả" class="d-flex justify-content-between list-item-js">
                                            <span class="name">Tất cả</span>
                                            <span class="icon-check co-green"><i class="fal fa-check"></i></span>
                                        </li>
                                        @foreach($listUsers as $item)
                                        <li value="{{$item->_id}}" text="{{$item->username}}" class="d-flex justify-content-between list-item-js">
                                            <span class="name">{{$item->username}}</span>
                                            <span class="icon-check co-green"><i class="fal fa-check"></i></span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group m-0 col-2 fs-13 row">
                                <label for="inputEmail3" class="col-form-label">Trạng thái</label>
                                <div class="flex-1 ml-3 position-relative">
                                    <input type="text" readonly class="fs-13 form-control pointer" name="reason" data-toggle="menu" data-target="#menu-status" placeholder="Chọn sản phẩm">
                                    <ul class="list bg-white" id="menu-status">
                                        <li value="-1" data-selected="true" text="Tất cả" class="d-flex justify-content-between list-item-js">
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
            <div class="create-user fs-14 h-100 bg-white mt-2 border-top base-table-layout">
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <span class="fs-25" id="title">Báo cáo doanh thu từ ngày 24/03/2020 đến 26/03/2020</span>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="base-table--data base-table-content flex-1 overflow-auto">
                            <table id="headerTable" class="table-filter w-100 bg-white">
                                <thead>
                                    <tr class="sub-header ">
                                        <th data-index="0" class="th-typeview-name">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">#</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-name">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Tài khoản</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Tên khách hàng</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Số điện thoại</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Ghi chú của sale</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Trạng thái đơn</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Ngày cập nhật</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Xem</span></div>
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
    var totals = [];
    var typeAccount = '{{$user->type_account}}';
    var stt = 0;
    const api = {
        getOrderList: function(callback) {
            stt = 0;
            lib.send.get('{{route("api.report.note")}}', callback, window.location.search);
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
            activity.setTotalPage(res.data.result);
            activity.setPaginate(res.data.pagination);
            Notiflix.Block.Remove('.base-table-content');
        },
        getData: function() {
            var stt = 0;
            loading.order.show('.base-table-content');
            api.getOrderList(activity.showDataListOrder);
        },
        setTime: function() {
            setTimeout(function() {
                start = $('[name="time_start"]').val().split('-');
                end = $('[name="time_end"]').val().split('-');
                $('#title').html('Báo cáo ghi chú đơn hàng từ ngày ' + start[2] + '/' + start[1] + '/' + start[0] + ' đến ' + end[2] + '/' + end[1] + '/' + end[0]);

                $('[name="time_start"]').data('daterangepicker').setStartDate(start[1] + '/' + start[2] + '/' + start[0]);
                $('[name="time_start"]').data('daterangepicker').setEndDate(start[1] + '/' + start[2] + '/' + start[0]);
                $('[name="time_end"]').data('daterangepicker').setStartDate(end[1] + '/' + end[2] + '/' + end[0]);
                $('[name="time_end"]').data('daterangepicker').setEndDate(end[1] + '/' + end[2] + '/' + end[0]);
            }, 0);
        },
        setForm: function() {
            const params = new URLSearchParams(window.location.search);
            let paramObj = {};
            if (!!!window.location.search.includes('time_start')) {
                now = "{{date('Y-m-')}}01";
                $('[name="time_start"]').val(now);
                lib.updateParams('time_start', now);
            }
            if (!!!window.location.search.includes('time_end')) {
                now = "{{date('Y-m-d')}}";
                $('[name="time_end"]').val(now);
                lib.updateParams('time_end', now);
            }
            activity.setTime();

            for (var key of params.keys()) {
                value = params.get(key);

                if (key == "source_id") {
                    if (!!$("[data-source='" + value + "']").length) {
                        $(".list-source--item").removeClass('active');
                        $("[data-source='" + value + "']").addClass('active');
                    }
                } else if (key == "user_id") {
                    $('.filter-user_id-js').val(value);
                } else if (key == "time_start" || key == "time_end" || key == "search") {
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
            try {
                stt += 1;
                dataHtml = '';
                if (!!!item.order) {
                    item.order = item.order_care;
                }
                var detail = '{{ route("site.order.detail", ":id") }}';
                detail = detail.replace(':id', item.order._id);

                reason = {
                    'wait': "<span class='alert-warning reason-style'>Chưa chốt được</span>",
                    'success': "<span class='alert-success reason-style'>Đơn đã chốt</span>",
                    'cancel': "<span class='alert-danger reason-style'>Đơn huỷ</span>",
                    'undefined': "<span class='alert-primary reason-style'>Đơn hàng mới</span>"
                }
                note = item.origin_note;
                if (!!!note) {
                    note = item.note;
                }
                dataHtml += `<tr>
                <td class="td-first text-center">
                    ` + stt + `
                </td>
                <td class="td-first text-center">
                    ` + item.customer.username + `
                </td>
                <td class="cell-hover-border td-typeview-name">
                    <div class="base_field_name"><a class="detail-js" href="` + detail + `" title="` + item.order.name + `" class="a_overflow_hidden">` + item.order.name + `</a></div>
                </td>
                <td class="cell-hover-border td-typeview-phones ">
                    ` + item.order.phone + `
                </td>
                <td class="cell-hover-border td-typeview-phones co-red bold white-space-normal">
                    ` + note + `
                </td>
                <td class="cell-hover-border td-typeview-phones">
                    ` + reason[item.reason] + `
                </td>
                <td class="cell-hover-border td-typeview-phones">
                    ` + item.created_at + `
                </td>
                <td class="cell-hover-border td-typeview-phones text-center">
                    <span><a href="` + detail + `"><i class="fal fa-edit"></i></a></span>
                </td>
                
            </tr>`;
            } catch (error) {
                
            }
            return dataHtml;
        },
        emptyData: function() {
            return '<td class="text-center p-3 bold fs-18" colspan="11">Không có dữ liệu nào</td>';
        }
    }
    $(function() {
        $('[timepicker="true"]').daterangepicker({
            singleDatePicker: true,
            autoUpdateInput: false
        }, function(start, end, label) {
            name = $(this)[0].element[0].name;
            time = start.format('Y-MM-DD');
            $('[name="' + name + '"]').val(time);
            lib.updateParams(name, time);
            activity.setTime();
            activity.getData();
        });

        $(document).on('click', '.btn-click-find-order-js', function() {
            inp = $('.inp-find-item-order-js').val();
            if (!!!inp) {
                lib.removeParams('search');
            } else {
                lib.updateParams('search', inp);
            }

            activity.getData();
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

        $(document).on('click', '#table-body-data tr', function() {
            self = $(this);
            inp = self.find('.stt-order-js');
            select = self.attr('data-selected');
            if (select == "true") {
                self.attr('data-selected', 'false');
                inp.prop('checked', false);
            } else {
                self.attr('data-selected', 'true');
                inp.prop('checked', true);
            }
        });
        $(document).on('dblclick', '.base-table--data tbody tr', function() {
            window.open($(this).find('.detail-js').attr('href'), '_blank');
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

        window.onclick = function(event) {
            if ($(event.target).hasClass('inp-data-user-reciver-js') || !!$(event.target).parents('.assign-order-js').html()) return;
            $('.assign-order-js').addClass('d-none');
        }

        activity.setForm();
        activity.getData();

    });
</script>
@endsection