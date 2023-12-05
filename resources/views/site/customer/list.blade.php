@extends('site.layout.master')
@section('title', 'Danh sách sản phẩm')

@php(
    $columns = [
        [
            'title' => 'Mã khách hàng',
        ],
        [
            'title' => 'Tên khách hàng',
        ],
        [
            'title' => 'Email khách hàng',
        ],
        [
            'title' => 'Số điện thoại',
        ],
        [
            'title' => 'Số lượng đơn đã đặt',
        ],
        [
            'title' => 'Số lượng đơn đã hoàn thành',
        ],
        [
            'title' => 'Số tiền đã thanh toán',
        ],
        [
            'title' => 'Thao tác',
        ],
    ]
)

@php(
    $dataTable = [
        'columns' => $columns,
        'config' => [
            'checkboxAll' => true,
        ],
        'search' => [
            'placeholder' => 'Tìm kiếm tên khách hàng, số điện thoại, email'
        ]
    ]
)

@section('content')
    <div class="app-content">
        <div class="section flex-1">
            <div class="page main-body flex flex-column">
                <div class="page__header">
                    <div class="flex-1">
                        <h6 class="page-title mb-1">
                            <strong>Danh sách khách hàng</strong>
                        </h6>
                        <span class="page-subtitle">Với những cửa hàng có hàng trăm sản phẩm, cập nhật thủ công từng thông
                            tin mô tả sản phẩm, giá bán và giá gốc sẽ tốn rất nhiều thời gian và công sức. Công cụ cập nhật
                            hàng loạt sẽ giúp bạn năng suất hơn.</span>
                    </div>
                    <div>
                        @if ($user->permission == 'admin')
                            <div class="btn btn-danger fs-13 btn-click-remove-js pointer">Xoá khách hàng</div>
                        @endif
                        <a v-click="{{ $callAjaxModal }}" width="1400px" href="{{route('site.customer.create')}}" class="btn btn-primary fs-13 pointer"> Thêm khách
                            hàng</a>
                    </div>
                </div>
                @include('site.uikit.table.light', $dataTable)
            </div>
        </div>
    </div>
@endsection

@section('custom_js')

    <script>
        const api = {
            getList: function(callback) {
                lib.send.get('{{ route('api.customer.getListCustomer') }}', callback, window.location.search);
            }
        }

        const activity = {
            showData: function(res) {
                if (res.success) {
                    element.table().html('');
                    if (!!res.data.data.length) {
                        res.data.data.forEach(item => {
                            resHtml = html.renderList(res.data, item);
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

                if (!!!window.location.search.includes('type_confirm_text') && !!!window.location.search.includes(
                        'search')) {
                    $('[name="filter_date"]').val(['created_at']);
                    lib.updateParams('filter_date_by', 'created_at');
                }

                for (var key of params.keys()) {
                    value = params.get(key);

                    try {
                        if (key == "source_id") {
                            if (!!$("[data-source='" + value + "']").length) {
                                $(".list-source--item").removeClass('active');
                                $("[data-source='" + value + "']").addClass('active');
                            }
                        } else if (key == "user_id") {
                            $('.filter-user_id-js').val(value);
                        } else if (key == "time_start" || key == "time_end" || key == "search") {
                            $('[name="' + key + '"]').val(value);
                        } else if (key == "filter_date_by") {
                            $('[name="filter_date"]').val([value]);
                        } else if (key == "user_reciver_id") {
                            $('.filter-user_reciver_id-js').val(value);
                        } else if (key.includes('[]')) {
                            for (var value of params.values()) {
                                id = key.replace('[]', '');
                                $('#' + value).prop('checked', true);
                                $('#' + id + value).prop('checked', true);
                            }
                        } else {
                            for (var value of params.values()) {
                                $('#' + key + value).prop('checked', true);
                                $('[name="' + key + '"]').prop('checked', true);
                            }
                        }
                    } catch (error) {

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
                var detail = '{{ route('site.customer.detail', ':id') }}';
                detail = detail.replace(':id', item._id);

                dataHtml = `<tr data-id="` + item._id + `">`;
                dataHtml += `
            <td class="td-first">
              <li class="checkbox_acount list-item-order">
                <input class="d-none stt-order-js" type="checkbox" id="` + item._id + `">
                <label class="label-checkbox base-table-item--checkbox" for="` + item._id + `"></label>
              </li>
            </td>
            <td class="cell-hover-border td-typeview-name view-medium white-space-normal">
                <div class="base_field_name"><a class="detail-js" href="` + detail + `" title="` + item.customer_id +
                    `" class="a_overflow_hidden">` + (item.customer_id || "") + `</a></div>
            </td>
            <td class="cell-hover-border td-typeview-phones ">
                <div class="base_field_phones"><span>` + item.name + `</div>
            </td>
            <td class="cell-hover-border">
                ` + item.email + `
            </td>
            <td class="cell-hover-border td-typeview-sale text-right">
                ` + item.phone + `
            </td>
            <td class="cell-hover-border td-typeview-sale text-right">
                ` + 0 + `
            </td>
            <td class="cell-hover-border td-typeview-sale text-right">
                <div class="js-value-container fix_width">` + 0 + `</div>
            </td>
            <td class="cell-hover-border td-typeview-sale text-right">
                ` + 0 + `
            </td>
            <td class="td-number text-center">
              <span><a href="` + detail + `"><i class="fal fa-edit"></i></a></span>
            </td>
        </tr>`;
                return dataHtml;
            },
            emptyData: function() {
                return '<td class="text-center p-3 bold fs-18" colspan="13">Không có dữ liệu khách hàng nào</td>';
            }
        }
        $(function() {

            $('.filter-reason-js').click(function() {
                self = $(this);
                lib.removeParams('page');
                if (self.is(":checked")) {
                    lib.updateParams('reason[]', self.val());
                } else {
                    lib.removeParams('reason[]', self.val());
                }
                activity.getData();
            });

            $('.filter-user-reciver-js').click(function() {
                self = $(this);
                lib.removeParams('page');
                if (self.is(":checked")) {
                    lib.updateParams('user_reciver_id', self.val());
                } else {
                    lib.removeParams('user_reciver_id', self.val());
                }
                activity.getData();
            });


            $('.filter-filter_status-js').click(function() {
                self = $(this);
                lib.removeParams('page');
                if (self.is(":checked")) {
                    lib.updateParams('filter_status[]', self.val());
                } else {
                    lib.removeParams('filter_status[]', self.val());
                }
                activity.getData();
            });


            $('.filter-filter_ship-js').click(function() {
                self = $(this);
                lib.removeParams('page');
                if (self.is(":checked")) {
                    lib.updateParams('filter_ship[]', self.val());
                } else {
                    lib.removeParams('filter_ship[]', self.val());
                }
                activity.getData();
            });

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

            $('.filter-user_id-js').change(function() {
                val = $(this).val();
                lib.removeParams('page');
                if (val == "-1") {
                    lib.removeParams('user_id');
                } else {
                    lib.updateParams('user_id', $(this).val());
                }
                activity.getData();
            });

            $('.filter-user_reciver_id-js').change(function() {
                val = $(this).val();
                lib.removeParams('page');
                if (val == "-1") {
                    lib.removeParams('user_reciver_id');
                } else {
                    lib.updateParams('user_reciver_id', $(this).val());
                }
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

            $(document).on('click', '.list-source--item-js', function(event) {
                event.preventDefault();
                lib.removeParams('page');
                $('.list-source--item').removeClass('active');
                $(this).addClass('active');

                var source = $(this).data('source');

                lib.updateParams('source_id', source);
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
