@php
    $alignArr = ['center' => 'text-center', 'right' => 'text-right', '' => 'text-left'];
    $js_array = json_encode($columns); 
@endphp

<div class="base-table">
    <div class="source-filter source-filter--layout">
        <ul class="list-source nav nav-tabs border-none">
            @if (isset($tabs))
                @foreach ($tabs as $tab)
                    <li data-value="{{ $tab['value'] ?? '' }}" v-click="{{ $filterStatus }}" class="list-source--item">
                        <a href="#">
                            <span>{{ $tab['title'] ?? '' }}</span>
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
    @if (isset($search))
        <div class="base-table-filter">
            <input type="text" placeholder="{{ $search['placeholder'] ?? '' }}"
                class="form-control fw-300 fs-15 shadow-none">
        </div>
    @endif
    <div class="row flex-1">
        <div class="col-12 h-100">
            <div class="base-table-content base-table-layout flex flex-column">
                <div class="base-table--data flex-1 overflow-auto">
                    <table id="headerTable" class="table-filter w-100"
                        data-json="{{ isset($config) && json_encode($config) }}">
                        <thead>
                            <tr class="sub-header">
                                @if (isset($config['checkboxAll']) && $config['checkboxAll'])
                                    <th>
                                        <div class="th-container" style="top: 0px; border-bottom: 0px;">
                                            <li class="checkbox_acount list-item-order">
                                                <input class="d-none stt-order-all-js" type="checkbox"
                                                    id="stt-list-order-all">
                                                <label class="label-checkbox base-table-item--checkbox"
                                                    for="stt-list-order-all"></label>
                                            </li>
                                        </div>
                                    </th>
                                @endif
                                @if (isset($columns))
                                    @foreach ($columns as $column)
                                        <th data-index="0"
                                            class="th-typeview-name {{ $alignArr[$column['align'] ?? ''] }}">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span
                                                    class="vg-label fix_width"
                                                    style="">{{ $column['title'] }}</span>
                                            </div>
                                        </th>
                                    @endforeach
                                @endif
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
                                <select class="ml-2" style="height: 30px; width: 50px" name="count_page"
                                    id="count_page" class="mx-1">
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


@section('custom_js')
    <script>
        var columns = @json($columns);
        var columnsName = [];
        var isUseCheckbox = Boolean(parseInt('{{ $config['checkboxAll'] }}'));
        @if (isset($columns))
            @foreach ($columns as $key => $column)
                columnsName.push(`{{ $column['name'] ?? '' }}`);
            @endforeach
        @endif

        const api = {
            getList: function(callback) {
                lib.send.get('{{ $route_list ?? '' }}', callback, window.location.search);
            }
        }

        const activity = {
            showData: function(res) {
                if (res.success) {
                    element.table().html('');
                    var items = res?.data?.data?.items ?? res?.data?.items ?? res?.data?.data ?? [];
                    if (!!items.length) {
                        items.forEach(item => {
                            if (typeof handleResponse == "function") {
                                item = handleResponse(item);
                            }
                            var resHtml = html.newRows(item);
                            columns.forEach(column => {
                                if (typeof item[column?.name] != "undefined") {
                                    resHtml = html.renderRow(item[column.name], resHtml, column);
                                }else{
                                    resHtml = html.renderRow(column?.default ?? "", resHtml);
                                }
                            });

                            resHtml = html.endRows(resHtml);
                            element.table().append(resHtml);
                        });
                    } else {
                        element.table().html(html.emptyData());
                    }

                }

                Notiflix.Block.Remove('.base-table-content');
                activity.setTotalPage(res.data);
                activity.setPaginate(res.data.pagination);
            },
            getData: function() {
                loading.order.show('.base-table-content');
                api.getList(activity.showData);
            },
            setForm: function() {
                const params = new URLSearchParams(window.location.search);
                let paramObj = {};
                if (!window.location.search.includes('status') || window.location.search.includes('status=all')) {
                    $('[data-value="all"]').addClass('active');
                    lib.updateParams('status', 'all');
                    lib.updateParams('limit', $('#count_page').val());
                }

                for (var key of params.keys()) {
                    value = params.get(key);
                    if (key == "status") {
                        $('[data-value="' + value + '"]').addClass('active');
                    }
                    if (key == "limit") {
                        $('[name="count_page"]').val(value);
                    }
                }
            },
            setPaginate: function(data) {
                element.paginate().html(data);
            },
            setTotalPage: function(data) {
                $('#all_record').html(data.total);
                $('#count_page').val(data.per_page || 10);

            }
        }

        const html = {
            renderList: function(res, item) {

                var detail = '{{ route('site.customer.detail', ':id') }}';
                detail = detail.replace(':id', item._id);

                dataHtml = `<tr data-id="` + item._id + `">`;
                if (isUseCheckbox) {
                    dataHtml += `
                        <td class="td-first">
                            <li class="checkbox_acount list-item-order">
                                <input class="d-none stt-order-js" type="checkbox" id="` + item._id + `">
                                <label class="label-checkbox base-table-item--checkbox" for="` + item._id + `"></label>
                            </li>
                        </td>`;
                }

                dataHtml += `
                    <td class="cell-hover-border td-typeview-name view-medium white-space-normal">
                        <div class="base_field_name"><a class="detail-js" href="` + detail + `" title="` + item
                    .shopName +
                    `" class="a_overflow_hidden">` + (item.shopName || "") + `</a></div>
                    </td>
                    <td class="cell-hover-border td-typeview-phones ">
                        <div class="base_field_phones"><span>` + 'Tiktok' + `</div>
                    </td>
                    <td class="cell-hover-border td-typeview-phones ">
                        <div class="base_field_phones"><span>` + item.shopCode + `</div>
                    </td>
                    <td class="cell-hover-border text-right">
                        ` + 0 + `
                    </td>
                    <td class="cell-hover-border td-typeview-sale text-right">
                        ` + 0 + `
                    </td>
                    <td class="cell-hover-border td-typeview-sale">Active</td>
                    <td class="td-number text-center">
                    <span><a href="` + detail + `"><i class="fal fa-edit"></i></a></span>
                    </td>
                </tr>`;
                return dataHtml;
            },
            renderRow: function(value, rows = "", column = {}) {
                alignClass = column?.align == "right" ?  "text-right" : (column?.align == "center" ?  "text-center" : "");
   
                if (column?.type == "image") {
                    urlImg = value.includes("https://") ? value : (value == "" ? window.ASSETS_BASE_URI + "no-photo.jpg" : window.ASSETS_BASE_URI + value)
                    dataHtml = rows + `<td class="cell-hover-border cell-images ` + alignClass + `">
                        <img class="img" src="` + urlImg + `" />
                    </td>`;
                } else {
                    dataHtml = rows + `<td class="cell-hover-border ` + alignClass + `">
                        <div class="base_field_phones"><span>` + value + `</span></div>
                    </td>`;
                }
                return dataHtml;
            },
            newRows: function(item) {
                dataHtml = `<tr data-id="` + item?.id + `">`;
                if (isUseCheckbox) {
                    dataHtml += `
                        <td class="td-first">
                            <li class="checkbox_acount list-item-order">
                                <input class="d-none stt-order-js" type="checkbox" id="` + item._id + `">
                                <label class="label-checkbox base-table-item--checkbox" for="` + item._id + `"></label>
                            </li>
                        </td>`;
                }
                return dataHtml;
            },
            endRows: function(rows) {
                dataHtml = rows + `</tr>`;
                return dataHtml;
            },
            emptyData: function() {
                return '<td class="text-center p-3 bold fs-18" colspan="13">Không có dữ liệu nào!</td>';
            }
        }
        $(function() {

            $('#count_page').change(function() {
                lib.removeParams('page');
                lib.updateParams('limit', $(this).val());
                activity.getData();
            });

            $('.inp-data-user-reciver-js').click(function() {
                self = $(this);
                list = self.closest('.form-group').find('.assign-order-js');
                list.removeClass('d-none');
                list.find('.inp-find-user-js').focus();
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

            $(document).on('click', '[v-click="{{ $filterStatus }}"]', function(event) {
                event.preventDefault();
                lib.removeParams('page');
                $('[v-click="{{ $filterStatus }}"]').removeClass('active');
                $(this).addClass('active');

                var value = $(this).data('value');

                lib.updateParams('status', value);
                activity.getData();
            });

            $(document).on('click', '.list-item-js', function() {

                select = $(this).attr('data-selected');
                inpReciver = $('.inp-data-user-reciver-js');
                name = $(this).find('.name').html();
                if (select == "true") {
                    $(this).attr('data-selected', 'false');
                    inpReciver.val(inpReciver.val().replace(name + ',', '').replace(name, ''));
                } else {
                    $('.assign-order-js').find('li').attr('data-selected', 'false');
                    $(this).attr('data-selected', 'true');
                    inpReciver.val(name);
                }
                $('.inp-find-user-js').focus();
            });

            $(document).on('click', '.btn-data-user-reciver-js', function() {
                list = $('.list-item-js');
                var order = $('#table-body-data tr');
                let checkReciver = false;
                checkOrder = false;
                var dataReciver = [];
                dataOrder = [];

                $.each(order, function(index, value) {
                    item = $(value);
                    if (item.attr('data-selected') == "true") {
                        dataOrder.push(item.attr('data-id'));
                        checkOrder = true;
                    }
                });
                if (!checkOrder) {
                    Notify.show.error('Bạn cần chọn ít nhất 1 đơn hàng để gán đơn');
                    return;
                }

                $.each(list, function(index, value) {
                    item = $(value);
                    if (item.attr('data-selected') == "true") {
                        dataReciver = item.attr('data-uid');
                        checkReciver = true;
                    }
                });
                if (!checkReciver) {
                    Notify.show.error('Bạn cần chọn ít nhất 1 người để gán đơn');
                    return;
                }
                var params = {
                    'user_reciver_id': dataReciver,
                    'order_id': dataOrder
                }
                lib.send.post('{{ route('api.order.assign.save') }}', function(res) {
                    if (res.success) {
                        Notify.show.success(res.msg);
                        activity.getData();
                        $('.inp-data-user-reciver-js').val('');
                        $('.list-item-js').attr('data-selected', false);
                        $('.stt-order-all-js').prop('checked', false);
                    } else {
                        Notify.show.error(res.msg);
                    }
                }, params);
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

            $(document).on('click', '.base-table-item--checkbox', function() {
                $(this).closest('tr').trigger('click');
            });
            $(document).on('click', '.stt-order-all-js', function() {
                self = $(this);
                inp = $('.stt-order-js');
                select = self.is(":checked");

                if (select) {
                    self.prop('checked', true);
                    inp.prop('checked', true);
                    $('#table-body-data tr').attr('data-selected', 'true');
                } else {
                    self.prop('checked', false);
                    inp.prop('checked', false);
                    $('#table-body-data tr').attr('data-selected', 'false');
                }
            });

            $(document).on('keyup', '.inp-find-user-js', function() {
                searchData($(this));
            });

            $(document).on('click', '[name="filter_date"]', function() {
                lib.removeParams('page');
                val = $('[name="filter_date"]:checked').val();
                if (val == "created_at" || val == "date_reciver") {
                    lib.updateParams('filter_date_by', val);
                    lib.removeParams('type_confirm_text');
                } else {
                    lib.updateParams('filter_date_by', 'date_confirm');
                    lib.updateParams('type_confirm_text', val);
                }


                activity.getData();
            });

            $(document).on('dblclick', '.base-table--data tbody tr', function() {
                window.location.href = $(this).find('.detail-js').attr('href');
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

            $(document).on('keyup', '.inp-find-item-order-js', function() {
                val = $(this).val();
                if (!!!val) {
                    lib.removeParams('search');
                    activity.setForm();
                    activity.getData();
                }
            });

            $('.btn-click-remove-time-start-js').click(function() {
                $('input[name="time_start"]').val('');
                lib.removeParams('time_start');
                activity.getData();
            });

            $('.btn-click-remove-time-end-js').click(function() {
                $('input[name="time_end"]').val('');
                lib.removeParams('time_end');
                activity.getData();
            });

            $(document).on('click', '.btn-click-remove-js', function() {
                list = $('.list-item-js');
                var order = $('#table-body-data tr');
                checkOrder = false;
                dataOrder = [];

                $.each(order, function(index, value) {
                    item = $(value);
                    if (item.attr('data-selected') == "true") {
                        dataOrder.push(item.attr('data-id'));
                        checkOrder = true;
                    }
                });
                if (!checkOrder) {
                    Notify.show.error('Bạn chưa chọn khách hàng cần xoá!');
                    return;
                }
                params = {
                    _id: dataOrder
                };

                Notify.show.confirm(function() {
                    lib.send.post('{{ route('api.customer.remove') }}', function(res) {
                        if (res.success) {
                            Notify.show.success(res.msg);
                            activity.getData();
                        } else {
                            Notify.show.error(res.msg);
                        }
                    }, params);
                });
            });

            window.onclick = function(event) {
                if ($(event.target).hasClass('inp-data-user-reciver-js') || !!$(event.target).parents(
                        '.assign-order-js').html()) return;
                $('.assign-order-js').addClass('d-none');
            }
            activity.setForm();
            activity.getData();


            $('[timepicker="true"]').daterangepicker({
                singleDatePicker: true,
                autoUpdateInput: false
            }, function(start, end, label) {
                name = $(this)[0].element[0].name;
                time = start.format('Y-MM-DD');
                $('[name="' + name + '"]').val(time);
                lib.updateParams(name, time);

                val = $('[name="filter_date"]:checked').val();
                if (val == "created_at" || val == "date_reciver") {
                    lib.removeParams('type_confirm_text');
                } else {
                    lib.updateParams('type_confirm_text', val);
                }
                activity.getData();
            });

        });
    </script>
@endsection
