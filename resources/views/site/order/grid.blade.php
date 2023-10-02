@extends('site.layout.master', ['useTopHeader' => 'light'])
@section('title', 'Danh sách đơn hàng')
@section('page-title', 'B2B - Quản trị đơn hàng')
@section('navigate')
<li class="active">
    <a href=""><span>Danh sách</span></a>
</li>
<li>
    <a href=""><span>Báo cáo nhanh</span></a>
</li>
<li>
    <a href=""><span>Chỉnh sửa cột</span></a>
</li>
<li>
    <a href=""><span>Nhiệm vụ</span></a>
</li>
<li>
    <a href=""><span>Nhiệm vụ</span></a>
</li>
@endsection

@section('header-right')
<div class="d-flex align-items-center">
    <div class="btn-group mx-2" role="group">
        <button type="button" class="btn btn-success fs-13"><i class="fal fa-th-large"></i></button>
        <button type="button" class="btn btn-outline-success fs-13"><i class="fal fa-bars"></i></button>
    </div>
    <div class="btn btn-success fs-13 pointer mx-2"> <a href="{{route('site.order.create')}}">Tạo đơn mới</a> </div>
</div>
@endsection


@section('content')
<div class="app-content">
    <div class="section p-0">
        <div class="main-body flex flex-column">
            <div class="row flex-1">
                <div class="col-12 h-100">
                    <div class="order-list__grid overflow-auto w-100 h-100">
                        <div class="container-fluid p-0 h-100">
                            <div id="table-body-data" class="order-list__columns__cotainer m-0 row flex-nowrap base-table-content"></div>
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
    const api = {
        getOrderList: function (callback) {
            lib.send.get('{{route("api.order.getAllListOrder")}}', callback, window.location.search);
        },
        getLabelList: function (callback) {
            lib.send.get('{{route("api.order.getListLabel")}}', callback, window.location.search);
        }
    }

    const activity = {
        showDataListOrder: function (res) {
            if (res.success) {
                if (!!res.data.result.length) {
                    res.data.result.forEach(item => {
                        resHtml = html.renderList(item);
                        element.column(`[data-column-id="${item.label_id}"]`).find(`[data-element="body"]`).append(resHtml);
                    });
                } else {
                    element.column(`[data-column-id="${item.label_id}"]`).find(`[data-element="body"]`).html(html.emptyData());
                }
            }
            Notiflix.Block.Remove('.base-table-content');
            activity.setTotalPage(res.data.result);
            activity.setPaginate(res.data.pagination);
        },
        showDataListLabel: function (res) {
            if (res.success) {
                element.table().html('');
                if (!!res.data) {
                    element.table().html(res.data);
                } else {
                    element.table().html(html.emptyData());
                }
            }
        },
        getData: function () {
            loading.order.show('.base-table-content');
            api.getLabelList(function (response) {
                activity.showDataListLabel(response);
                api.getOrderList(activity.showDataListOrder);
            });
        },
        setForm: function () {
            const params = new URLSearchParams(window.location.search);
            let paramObj = {};

            if (!!!window.location.search.includes('type_confirm_text') && !!!window.location.search.includes('search')) {
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
        setPaginate: function (data) {
            element.paginate().html(data);
        },
        setTotalPage: function (data) {
            $('#all_record').html(data.total);
            $('#count_page').val(data.per_page);

        }
    }

    const html = {
        renderList: function (item) {
            return item.html;
        },
        emptyData: function () {
            return '<td class="text-center p-3 bold fs-18" colspan="13">Không có dữ liệu khách hàng nào</td>';
        }
    }
    $(function () {

        $('.filter-reason-js').click(function () {
            self = $(this);
            lib.removeParams('page');
            if (self.is(":checked")) {
                lib.updateParams('reason[]', self.val());
            } else {
                lib.removeParams('reason[]', self.val());
            }
            activity.getData();
        });

        $('.filter-user-reciver-js').click(function () {
            self = $(this);
            lib.removeParams('page');
            if (self.is(":checked")) {
                lib.updateParams('user_reciver_id', self.val());
            } else {
                lib.removeParams('user_reciver_id', self.val());
            }
            activity.getData();
        });


        $('.filter-filter_status-js').click(function () {
            self = $(this);
            lib.removeParams('page');
            if (self.is(":checked")) {
                lib.updateParams('filter_status[]', self.val());
            } else {
                lib.removeParams('filter_status[]', self.val());
            }
            activity.getData();
        });


        $('.filter-filter_ship-js').click(function () {
            self = $(this);
            lib.removeParams('page');
            if (self.is(":checked")) {
                lib.updateParams('filter_ship[]', self.val());
            } else {
                lib.removeParams('filter_ship[]', self.val());
            }
            activity.getData();
        });

        $('#count_page').change(function () {
            lib.removeParams('page');
            lib.updateParams('limit', $(this).val());
            activity.getData();
        });

        $('.inp-data-user-reciver-js').click(function () {
            self = $(this);
            list = self.closest('.form-group').find('.assign-order-js');
            list.removeClass('d-none');
            list.find('.inp-find-user-js').focus();
        });

        $('.filter-user_id-js').change(function () {
            val = $(this).val();
            lib.removeParams('page');
            if (val == "-1") {
                lib.removeParams('user_id');
            } else {
                lib.updateParams('user_id', $(this).val());
            }
            activity.getData();
        });

        $('.filter-user_reciver_id-js').change(function () {
            val = $(this).val();
            lib.removeParams('page');
            if (val == "-1") {
                lib.removeParams('user_reciver_id');
            } else {
                lib.updateParams('user_reciver_id', $(this).val());
            }
            activity.getData();
        });


        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();

            $('.pagination li').removeClass('active');
            $(this).parent('li').addClass('active');

            var myurl = $(this).attr('href');
            var page = $(this).attr('href').split('page=')[1];

            lib.updateParams('page', page);
            activity.getData();
        });

        $(document).on('click', '.list-source--item-js', function (event) {
            event.preventDefault();
            lib.removeParams('page');
            $('.list-source--item').removeClass('active');
            $(this).addClass('active');

            var source = $(this).data('source');

            lib.updateParams('source_id', source);
            activity.getData();
        });

        $(document).on('click', '.list-item-js', function () {

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

        $(document).on('click', '.btn-data-user-reciver-js', function () {
            list = $('.list-item-js');
            var order = $('#table-body-data tr');
            let checkReciver = false;
            checkOrder = false;
            var dataReciver = [];
            dataOrder = [];

            $.each(order, function (index, value) {
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

            $.each(list, function (index, value) {
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
            lib.send.post('{{route("api.order.assign.save")}}', function (res) {
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

        $(document).on('click', '#table-body-data tr', function () {
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

        $(document).on('click', '.base-table-item--checkbox', function () {
            $(this).closest('tr').trigger('click');
        });
        $(document).on('click', '.stt-order-all-js', function () {
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

        $(document).on('keyup', '.inp-find-user-js', function () {
            searchData($(this));
        });

        $(document).on('click', '[name="filter_date"]', function () {
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

        $(document).on('dblclick', '.base-table--data tbody tr', function () {
            window.location.href = $(this).find('.detail-js').attr('href');
        });

        $(document).on('click', '.btn-click-find-order-js', function () {
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

        $(document).on('keyup', '.inp-find-item-order-js', function () {
            val = $(this).val();
            if (!!!val) {
                lib.removeParams('search');
                activity.setForm();
                activity.getData();
            }
        });

        $('.btn-click-remove-time-start-js').click(function () {
            $('input[name="time_start"]').val('');
            lib.removeParams('time_start');
            activity.getData();
        });

        $('.btn-click-remove-time-end-js').click(function () {
            $('input[name="time_end"]').val('');
            lib.removeParams('time_end');
            activity.getData();
        });

        $(document).on('click', '.btn-click-remove-js', function () {
            list = $('.list-item-js');
            var order = $('#table-body-data tr');
            checkOrder = false;
            dataOrder = [];

            $.each(order, function (index, value) {
                item = $(value);
                if (item.attr('data-selected') == "true") {
                    dataOrder.push(item.attr('data-id'));
                    checkOrder = true;
                }
            });
            if (!checkOrder) {
                Notify.show.error('Bạn chưa chọn đơn cần xoá!');
                return;
            }
            params = {
                _id: dataOrder
            };

            Notify.show.confirm(function () {
                lib.send.post('{{route("api.order.removeOrder")}}', function (res) {
                    if (res.success) {
                        Notify.show.success(res.msg);
                        activity.getData();
                    } else {
                        Notify.show.error(res.msg);
                    }
                }, params);
            });
        });

        window.onclick = function (event) {
            if ($(event.target).hasClass('inp-data-user-reciver-js') || !!$(event.target).parents('.assign-order-js').html()) return;
            $('.assign-order-js').addClass('d-none');
        }
        activity.setForm();
        activity.getData();


        $('[timepicker="true"]').daterangepicker({
            singleDatePicker: true,
            autoUpdateInput: false
        }, function (start, end, label) {
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