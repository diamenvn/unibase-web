@extends('site.layout.master')
@section('title', 'Danh sách đơn hàng')

@section('content')
<div class="app-content lading">
    <div class="section">
        <div class="main-body flex flex-column">
            <div class="search-filter fs-14">
                <div class="row">
                    <div class="col-md-12">
                        <div class="filter-area flex">
                            <div class="filter-item w-20">
                                <div class="title text-center">
                                    <i class="fal fa-calendar"></i>
                                    <span class="text">Thời gian</span>
                                </div>
                                <div class="item-search item-search-time">
                                    <ul class="content-item-filter item-filter-radio">
                                        <li>
                                            <input id="r1" name="options" ng-control="options" checked type="radio" class="ng-untouched ng-pristine ng-valid">
                                            <label class="label-radio" for="r1">
                                                <span></span>Tạo đơn</label>
                                        </li>
                                        <li>
                                            <input checked id="r2" name="options" ng-control="options" type="radio" class="ng-untouched ng-pristine ng-valid">
                                            <label class="label-radio" for="r2">
                                                <span></span>Gửi đơn</label>
                                        </li>
                                    </ul>
                                    <div class="search-time">
                                        <input id="timeDateRange2" class="input-control" name="daterange" placeholder="Thời gian bắt đầu" type="text">
                                    </div>
                                    <div class="search-time mt-2">
                                        <input id="timeDateRange" class="input-control" name="daterange" placeholder="Thời gian kết thúc" type="text">
                                    </div>


                                </div>
                            </div>
                            <div class="filter-item w-40">
                                <div class="title text-center">
                                    <i class="fal fa-book mr-1"></i>
                                    <span class="text">Tình trạng đơn hàng</span>
                                </div>
                                <div class="item-search item-search-time">
                                    <ul class="content-item-filter item-filter-radio">
                                        @foreach($actions as $key => $action)
                                        @if ($action->type == "filter_lading_ship")
                                        <li class="col-4 checkbox_acount">
                                            <input class="filter-filter_lading_ship-js" type="checkbox" value="{{$action->_id}}" id="{{$action->_id}}" name="filter_lading_ship">
                                            <label class="label-checkbox" for="{{$action->_id}}">{{$action->text}}</label>
                                        </li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="filter-item w-25">
                                <div class="title text-center">
                                    <i class="fal fa-truck mr-1" style="transform: scaleX(-1);"></i>
                                    <span class="text">Vận chuyển</span>
                                </div>
                                <div class="item-search item-search-ship">
                                    <ul class="content-item-filter item-filter-radio">
                                        @foreach($actions as $key => $action)
                                        @if ($action->type == "filter_ship")
                                        <li class="checkbox_acount">
                                            <input class="filter-filter_ship-js" type="checkbox" value="{{$action->_id}}" id="{{$action->_id}}" name="filter_ship">
                                            <label class="label-checkbox" for="{{$action->_id}}">{{$action->text}}</label>
                                        </li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="filter-item w-15">
                                <div class="title text-center">
                                    <i class="fal fa-truck mr-1" style="transform: scaleX(-1);"></i>
                                    <span class="text">Nguồn đơn từ nhân viên</span>
                                </div>
                                <div class="item-search item-search-time h-40 mt-2">
                                    <ul class="content-item-filter item-filter-radio m-0">
                                        <li class="checkbox_acount w-100 p-0">
                                            <select name="user_id" class="input-control h-30 filter-user_id-js" id="">
                                                <option selected value="-1">Tất cả</option>
                                                @foreach($userCompany as $customer)
                                                <option value="{{$customer->_id}}">{{$customer->name}}</option>
                                                @endforeach
                                            </select>
                                        </li>
                                    </ul>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
            <div class="row flex-1 overflow-auto mt-3">
                <div class="col-12 h-100">
                    <div class="base-table-content base-table-layout flex flex-column">
                        <div class="base-table--filter">
                            <div class="panel-heading-left panel-heading-title" id="panel-heading-title">
                                <h6 class="panel-title mb-1">
                                    <strong>Danh sách khách hàng</strong>
                                </h6>
                                (Tìm thấy : <span id="all_record">0</span> Khách hàng)
                            </div>
                            <div class="panel-heading-left d-flex align-items-center form-group m-0 search h-100" style="width: 280px">
                                <div class="input-group">
                                    <input type="text" class="form-control fs-14" placeholder="Mã vận đơn, số điện thoại" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary fs-14" type="button">Tìm</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="base-table--data flex-1 overflow-auto">
                            <table id="headerTable" class="table-filter d-block" style="min-width: 100%; width: 0px">
                                <thead>
                                    <tr class="sub-header">
                                        <th>
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;">
                                                <li class="checkbox_acount list-item-order">
                                                    <input class="d-none stt-order-all-js" type="checkbox" id="stt-list-order-all">
                                                    <label class="label-checkbox base-table-item--checkbox" for="stt-list-order-all"></label>
                                                </li>
                                            </div>
                                        </th>
                                        <th data-index="1" class="th-typeview-phones">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width">Tên người nhận</span></div>
                                        </th>
                                        <th data-index="2" class="th-typeview-emails">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width">Số điện thoại</span></div>
                                        </th>
                                        <th data-index="3" class="th-typeview-address">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width">Địa chỉ giao hàng</span></div>
                                        </th>
                                        <th data-index="4" class="th-typeview-sale">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width">Tổng tiền</span></div>
                                        </th>
                                        <th data-index="5" class="th-typeview-san_pham_quan_tam">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width">Đơn chốt bởi sale</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width">Sản phẩm</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width">Tình trạng</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width">Ngày nhận đơn</span></div>
                                        </th>
                                        <th data-index="0" class="th-typeview-value">
                                            <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width">Chỉnh sửa</span></div>
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
<div class="modal fade" id="modalDetailShip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLongTitle">Chi tiết đơn hàng</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info" role="alert">
                                <strong>Lưu ý:</strong> Dòng có dấu <span class="co-red">*</span> là những trường bắt buộc điền!
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tên khách hàng</label>
                                <input disabled class="form-control bg-white" name="name" type="text" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input disabled class="form-control bold co-red" name="phone" type="text" placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Địa chỉ</label>
                                <textarea disabled rows="3" class="form-control" name="address" type="text" placeholder=""></textarea>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="p-2 mb-2 h-100" style="border:1px solid #ddd; border-radius: 5px">
                                <div class="title text-bold fs-14 mb-2">Thông tin sản phẩm</div>
                                <table class="w-100">
                                    <thead>
                                        <tr>
                                            <th class="pb-2" style="width: 80px">Tên sản phẩm</th>
                                            <th class="pb-2" style="width: 80px">Số lượng</th>
                                            <th class="pb-2" style="width: 80px">Giá 1 sản phẩm</th>
                                        </tr>
                                    </thead>
                                    <tbody id="area-product">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Tình trạng đơn hàng <span class="co-red">*</span> </label>
                                <select class="form-control" name="filter_lading_ship">
                                    @foreach($actions as $key => $action)
                                    @if ($action->type == "filter_lading_ship")
                                    <option value="{{$action->_id}}">{{$action->text}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Đơn vị vận chuyển <span class="co-red">*</span></label>
                                <select class="form-control" name="filter_ship">
                                    @foreach($actions as $key => $action)
                                    @if ($action->type == "filter_ship")
                                    <option value="{{$action->_id}}">{{$action->text}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" data-id="" class="btn btn-success btn-ship-confirm-js">Xác nhận gửi hàng</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')

<script>
    const api = {
        getOrderList: function(callback) {
            lib.send.get('{{route("api.lading.getList")}}', callback, window.location.search);
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

            address = '';
            if (!!item.address) {
                address += item.address + ' - ';
            }
            if (!!item.town) {
                address += item.town + ' - ';
            }
            if (!!item.district) {
                address += item.district + ' - ';
            }
            if (!!item.provin) {
                address += item.provin;
            }
            product = note = '';
            arrProductExist = arrListProduct = [];
            item.product.forEach(productItem => {
                if (!arrProductExist.includes(productItem.product_id)) {
                    arrListProduct[productItem.product_id] = {
                        amount: parseInt(productItem.amount),
                        name: res.product[productItem.product_id].product_name
                    }
                    arrProductExist.push(productItem.product_id);
                } else {
                    arrListProduct[productItem.product_id] = {
                        amount: parseInt(arrListProduct[productItem.product_id].amount) + parseInt(productItem.amount),
                        name: res.product[productItem.product_id].product_name
                    }
                }
            });
            arrListProduct.forEach(productItem => {
                product += '<span class="bg-info d-inline-block co-white p-1 px-2 mx-2" style="border-radius: 10px">' + arrListProduct[productItem].amount + ' ' + arrListProduct[productItem].name + '</span>';
            });
            product = product.substring(0, product.length - 2);
            filter = "<span class='alert-primary reason-style'>Đơn chưa xử lý</span>";
            if (!!item.activity) {
                filter = "<span class='alert-warning reason-style'>" + item.activity.text + "</span>";
            }
            if (!!item.note_ship) {
                note = item.note_ship;
            }
            dataHtml = `
        <tr data-id="` + item.order_id + `">
            <td class="td-first">
              <li class="checkbox_acount list-item-order">
                <input class="d-none stt-order-js" type="checkbox" id="` + item._id + `">
                <label class="label-checkbox base-table-item--checkbox" for="` + item._id + `"></label>
              </li>
            </td>
            <td class="cell-hover-border td-typeview-name ">
                <div class="base_field_name"><a title="` + item.name + `" class="a_overflow_hidden">` + item.name + `</a></div>
            </td>
            <td class="cell-hover-border td-typeview-phones ">
                <div class="base_field_phones"><span>` + item.phone + `</span></div>
            </td>
            <td class="cell-hover-border td-typeview-address ">
                <div class="base_field_emails"><span>` + address + `</span></div>
            </td>
            <td class="cell-hover-border td-typeview-ngay_lead_chuyen_sale ">
                ` + format_curency(item.total) + ` VNĐ
            </td>
            <td class="cell-hover-border td-typeview-sale ">
                 <span class="user--reciver mr-2">` + item.customer.name + `<i class="fas fa-star ml-1 fs-12 text-warning"></i></span>
            </td>
            <td class="cell-hover-border td-typeview-san_pham_quan_tam ">
                <div class="js-value-container fix_width">` + product + `</div>
            </td>
            <td class="cell-hover-border td-typeview-value ">
                <div class="js-value-container fix_width">` + filter + `</div>
            </td>
            <td class="td-number">
              <span>` + item.created_at + `</span>
            </td>
            <td class="td-number text-center btn-edit-order-js">
              <span><a href="javascript:void(0)"><i class="fal fa-edit"></i></a></span>
            </td>
        </tr>`;
            return dataHtml;
        },
        emptyData: function() {
            return '<td class="text-center p-3 bold fs-18" colspan="10">Không có dữ liệu khách hàng nào</td>';
        }
    }
    $(function() {

        $('.filter-filter_lading_ship-js').click(function() {
            self = $(this);
            if (self.is(":checked")) {
                lib.updateParams('filter_lading_ship[]', self.val());
            } else {
                lib.removeParams('filter_lading_ship[]', self.val());
            }
            activity.getData();
        });


        $('.filter-filter_ship-js').click(function() {
            self = $(this);
            if (self.is(":checked")) {
                lib.updateParams('filter_ship[]', self.val());
            } else {
                lib.removeParams('filter_ship[]', self.val());
            }
            activity.getData();
        });

        $('#count_page').change(function() {
            lib.updateParams('limit', $(this).val());
            activity.getData();
        });



        $('.filter-user_id-js').change(function() {
            val = $(this).val();
            if (val == "-1") {
                lib.removeParams('user_id');
            } else {
                lib.updateParams('user_id', $(this).val());
            }
            activity.getData();
        });

        $('.filter-company_mkt_id-js').change(function() {
            val = $(this).val();
            if (val == "-1") {
                lib.removeParams('company_mkt_id');
            } else {
                lib.updateParams('company_mkt_id', $(this).val());
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

        $(document).on('click', '.btn-edit-order-js', function() {
            id = $(this).closest('tr').attr('data-id');
            link = '{{ route("api.lading.getDetail", ":id") }}';
            link = link.replace(':id', id);
            elm = $('#modalDetailShip').find('.modal-dialog');
            Notiflix.Block.Circle('#modalDetailShip .modal-dialog', 'Đang lấy dữ liệu');
            $('#modalDetailShip').find('.btn-ship-confirm-js').attr('data-id', id);
            $('#modalDetailShip').modal();
            lib.send.get(link, function(res) {
                if (res.success) {
                    updateModal(res);
                }else{
                    Notify.show.error(res.msg);
                }
                Notiflix.Block.Remove('#modalDetailShip .modal-dialog');
            });
        });

        $(document).on('click', '.btn-ship-confirm-js', function(){
            modal = $('#modalDetailShip');
            id = $(this).attr('data-id');
            link = '{{ route("api.lading.detail.save", ":id") }}';
            link = link.replace(':id', id);
            params = {
                'filter_lading_ship': modal.find('*[name="filter_lading_ship"]').val(),
                'filter_ship': modal.find('*[name="filter_ship"]').val()
            }
            lib.send.post(link, function(res) {
                if (res.success) {
                    Notify.show.success(res.msg);
                    $('#modalDetailShip').modal('hide');
                    activity.getData();
                }else{
                    Notify.show.error(res.msg);
                }
                Notiflix.Block.Remove('#modalDetailShip .modal-dialog');
            }, params);
        });

        activity.getData();
        activity.setForm();
    });

    updateModal = function(res) {
        modal = $('#modalDetailShip');
        modal.find('#area-product').html('');
        $.each(res.data.order, function(key, value) {
            modal.find('*[name="' + key + '"]').val(value);
            if (key == "product") {
                value.forEach(product => {
                    elm = `<tr id="ship-order">
                                <td style="padding-right: 7px;padding-bottom: 10px" class="relative"><input disabled name="ship-amount[]" class="form-control amount" type="text" placeholder="" value="` + res.data.product[product.product_id].product_name +`"></td>
                                <td style="padding: 0px 14px;padding-bottom: 10px"><input disabled name="ship-amount[]" class="form-control amount" data-type="currency" type="text" value="` + product.amount + `"></td>
                                <td style="padding-left: 7px;padding-bottom: 10px"><input disabled name="ship-price[]" data-type="currency" class="form-control price" type="text" value="` + format_curency(product.price) + `"></td>
                            </tr>`;
                    modal.find('#area-product').append(elm);
                })
            }
        });

    }
</script>
@endsection