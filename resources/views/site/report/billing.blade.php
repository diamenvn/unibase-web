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
                            <div class="form-group m-0 col-2 fs-13 row">
                                <label for="inputEmail3" class="col-form-label">Sản phẩm</label>
                                <div class="flex-1 ml-3 position-relative">
                                    <input type="text" readonly class="fs-13 form-control pointer" name="product_id" data-toggle="menu" data-target="#menu-product" placeholder="Chọn sản phẩm">
                                    <ul class="list bg-white" id="menu-product">
                                        <li value="-1" data-selected="true" text="Tất cả" class="d-flex justify-content-between list-item-js">
                                            <span class="name">Tất cả</span>
                                            <span class="icon-check co-green"><i class="fal fa-check"></i></span>
                                        </li>
                                        @foreach($product as $item)
                                        <li value="{{$item->_id}}" text="{{$item->product_name}}" class="d-flex justify-content-between list-item-js">
                                            <span class="name">{{$item->product_name}}</span>
                                            <span class="icon-check co-green"><i class="fal fa-check"></i></span>
                                        </li>
                                        @endforeach
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
                                    <tr class="text-center merge">
                                        <th colspan="1">#</th>
                                        <th class="text-upcase bold co-red" colspan="2">Số lượng</th>
                                        <th class="text-upcase bold co-red" colspan="4">Vận đơn</th>
                                        <th class="text-upcase bold co-red" colspan="5">Doanh số</th>
                                    </tr>
                                    <tr class="sub-header ">
                                        <th data-index="0" class="th-typeview-name">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Nhân sự</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Số lượng SĐT</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">SL đơn chốt</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">DT đơn chốt</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Xác nhận</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Thành công</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Hoàn đơn</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Tỷ lệ chốt đơn</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">DS Chuyển/SĐT</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">CPQC/Doanh thu</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">Giá số</span></div>
                                        </th>
                                        <th data-index="1">
                                            <div class="th-container text-center" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width text-upcase" style="">DS/Số đơn</span></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table-body-data">

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


@section('custom_js')

<script>
    var totals = [];
    var typeAccount = '{{$user->type_account}}';
    var countPercent1 = sumPercent1 = sumPercent2 = sumPercent3 = 0;
    const api = {
        getOrderList: function(callback) {
            stt = 0;
            lib.send.get('{{route("api.report.getAllBilling")}}', callback, window.location.search);
        }
    }

    const activity = {
        showDataListOrder: function(res) {
            if (res.success) {
                element.table().html('');
                if (!!res.data.reports.length) {
                    res.data.reports.forEach(item => {
                        resHtml = html.renderList(res.data, item);
                        element.table().append(resHtml);
                    });
                    resHtml = html.renderTotal();
                    element.table().append(resHtml);
                } else {
                    element.table().html(html.emptyData());
                }

            }
            Notiflix.Block.Remove('.base-table-content');
        },
        getData: function() {
            totals['countPhone'] = 0;
            totals['countOrder'] = 0;
            totals['totalDone'] = 0;
            totals['totalSend'] = 0;
            totals['totalSuccess'] = 0;
            totals['totalReturn'] = 0;
            var countPercent1 = sumPercent1 = sumPercent2 = sumPercent3 = 0;
            loading.order.show('.base-table-content');
            api.getOrderList(activity.showDataListOrder);
        },
        setTime: function() {
            setTimeout(function(){
                start = $('[name="time_start"]').val().split('-');
                end = $('[name="time_end"]').val().split('-');
                $('#title').html('Báo cáo doanh thu MKT từ ngày ' + start[2] + '/' + start[1] + '/' + start[0] + ' đến ' + end[2] + '/' + end[1] + '/' + end[0]);
                
                $('[name="time_start"]').data('daterangepicker').setStartDate(start[1] + '/' + start[2] + '/' + start[0]);
                $('[name="time_start"]').data('daterangepicker').setEndDate(start[1] + '/' + start[2] + '/' + start[0]);
                $('[name="time_end"]').data('daterangepicker').setStartDate(end[1] + '/' + end[2] + '/' + end[0]);
                $('[name="time_end"]').data('daterangepicker').setEndDate(end[1] + '/' + end[2] + '/' + end[0]);
            },0);
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
        }
    }

    const html = {
        renderList: function(res, item) {
            dataHtml = '';
            percent1 = item.countOrder ? ((item.countOrder / item.countPhone) * 100).toFixed(2) : 0;
            percent2 = item.totalSend ? Math.round(item.totalSend / item.countPhone) : 0;
            percent3 = item.totalSend ? Math.round(item.totalSend / item.countOrder) : 0;

            totals['countPhone'] += item.countPhone;
            totals['countOrder'] += item.countOrder;
            totals['totalDone'] += item.totalDone;
            totals['totalSend'] += item.totalSend;
            totals['totalSuccess'] += item.totalSuccess;
            totals['totalReturn'] += item.totalReturn;

            if (!!item.countPhone) {
                countPercent1 += 1;
                sumPercent1 += parseInt(percent1);
                sumPercent2 += parseInt(percent2);
                sumPercent3 += parseInt(percent3);
            }

            dataHtml += `<tr>
            <td class="td-first text-center view-medium">
                ` + item.username + `
            </td>
            <td class="cell-hover-border td-typeview-name">
                ` + item.countPhone + `
            </td>
            <td class="cell-hover-border td-typeview-phones ">
                ` + item.countOrder + `
            </td>
            <td class="cell-hover-border td-typeview-phones">
                ` + format_curency(item.totalDone.toString()) + `đ
            </td>
            <td class="cell-hover-border td-typeview-phones">
                ` + format_curency(item.totalSend.toString()) + `đ
            </td>
            <td class="cell-hover-border td-typeview-phones">
                ` + format_curency(item.totalSuccess.toString()) + `đ
            </td>
            <td class="cell-hover-border td-typeview-phones">
                ` + format_curency(item.totalReturn.toString()) + `đ
            </td>
            <td class="cell-hover-border td-typeview-phones">
                ` + percent1 + `%
            </td>
            <td class="cell-hover-border td-typeview-phones">
                ` + format_curency(percent2.toString()) + `đ
            </td>
            <td class="cell-hover-border td-typeview-phones">
                0%
            </td>
            <td class="cell-hover-border td-typeview-phones">
                0đ
            </td>
            <td class="td-number text-center view-small">
                ` + format_curency(percent3.toString()) + `đ
            </td>
            
        </tr>`;
            return dataHtml;
        },
        renderTotal: function() {
            dataHtml = '';

            percent1 = sumPercent1 ? sumPercent1 / countPercent1 : 0;
            percent2 = sumPercent2 ? sumPercent2 / countPercent1 : 0;
            percent3 = sumPercent3 ? sumPercent3 / countPercent1 : 0;
            cpqcDoanhThu = '';
            if (typeAccount == "mkt") {
                cpqcDoanhThu = `<td class="cell-hover-border td-typeview-phones">0%</td>`;
            }

            dataHtml += `<tr style="background: beige; font-weight: 600">
            <td class="td-first text-center view-medium" style="color: green; font-weight: 600">
                ` + 'TỔNG CỘNG' + `
            </td>
            <td class="cell-hover-border td-typeview-name" style="color: green; font-weight: 600">
                ` + format_curency(totals['countPhone'].toString()) + `
            </td>
            <td class="cell-hover-border td-typeview-phones" style="color: green; font-weight: 600">
                ` + format_curency(totals['countOrder'].toString())+ `
            </td>
            <td class="cell-hover-border td-typeview-phones" style="color: green; font-weight: 600">
                ` + format_curency(totals['totalDone'].toString()) + `đ
            </td>
            <td class="cell-hover-border td-typeview-phones" style="color: green; font-weight: 600">
                ` + format_curency(totals['totalSend'].toString()) + `đ
            </td>
            <td class="cell-hover-border td-typeview-phones" style="color: green; font-weight: 600">
                ` + format_curency(totals['totalSuccess'].toString()) + `đ
            </td>
            <td class="cell-hover-border td-typeview-phones" style="color: green; font-weight: 600">
                ` + format_curency(totals['totalReturn'].toString()) + `đ
            </td>
            <td class="cell-hover-border td-typeview-phones" style="color: green; font-weight: 600">
               ` + Math.round(percent1) + `%
            </td>
            <td class="cell-hover-border td-typeview-phones" style="color: green; font-weight: 600">
            ` + format_curency(Math.round(percent2).toString()) +  `đ
            </td>
            <td class="cell-hover-border td-typeview-phones" style="color: green; font-weight: 600">
                0%
            </td>
            <td class="cell-hover-border td-typeview-phones" style="color: green; font-weight: 600">
                0đ
            </td>
            <td class="td-number text-center view-small" style="color: green; font-weight: 600">
            ` + format_curency(Math.round(percent3).toString()) + `đ
            </td>
            
        </tr>`;
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

        window.onclick = function(event) {
            if ($(event.target).hasClass('inp-data-user-reciver-js') || !!$(event.target).parents('.assign-order-js').html()) return;
            $('.assign-order-js').addClass('d-none');
        }

        activity.setForm();
        activity.getData();

    });
</script>
@endsection