@extends('site.layout.master')
@section('title', 'Vận đơn & Kế toán')

@section('content')
<div class="app-content">
  <div class="section">
    <div class="main-body flex flex-column">
      <div class="search-filter fs-14">
        <div class="row">
          <div class="col-md-12">
            <div class="filter-area flex">
              <div class="filter-item w-25">
                <div class="title text-center">
                  <i class="fal fa-calendar"></i>
                  <span class="text">Thời gian</span>
                </div>
                <div class="item-search item-search-time">
                  <ul class="content-item-filter item-filter-radio">
                    <li>
                      <input checked id="r1" name="filter_date" ng-control="options" checked value="date_success_order" type="radio" class="ng-untouched ng-pristine ng-valid">   
                      <label class="label-radio" for="r1">
                        <span></span>Ngày chốt đơn</label>
                    </li>
                    <li>
                      <input id="r2" name="filter_date" ng-control="options" type="radio" value="date_confirm" class="ng-untouched ng-pristine ng-valid">
                      <label class="label-radio" for="r2">
                        <span></span>Ngày xác nhận</label>
                    </li>
                    <li class="w-100">
                      <input id="r3" name="filter_date" ng-control="options" type="radio" value="date_user_created" class="ng-untouched ng-pristine ng-valid">
                      <label class="label-radio" for="r3">
                        <span>Ngày marketing tạo đơn</span>
                      </label>
                    </li>
                  </ul>
                  <div class="search-time">
                    <div class="input-group mb-2">
                        <input id="timeDateRange1" readonly timepicker="true" autocomplete="new-password" autocorrect="off" autocapitalize="none" spellcheck="false" class="form-control fs-14" name="time_start" placeholder="Thời gian bắt đầu" type="search">
                        <div class="input-group-append pointer btn-click-remove-time-start-js">
                          <div class="input-group-text fs-14"><i class="fal fa-times bold"></i></div>
                        </div>
                      </div>
                  </div>
                  <div class="search-time mt-2">
                    <div class="input-group mb-2">
                      <input id="timeDateRange" readonly timepicker="true" autocomplete="nope" autocorrect="off" autocapitalize="none" spellcheck="false" class="form-control fs-14" name="time_end" placeholder="Thời gian kết thúc" type="search">
                        <div class="input-group-append pointer btn-click-remove-time-end-js">
                          <div class="input-group-text fs-14"><i class="fal fa-times bold"></i></div>
                        </div>
                      </div>
                  </div>


                </div>
              </div>
              <div class="filter-item w-35">
                <div class="title text-center">
                  <i class="fal fa-book mr-1"></i>
                  <span class="text">Tình trạng đơn hàng</span>
                </div>
                <div class="item-search item-search-time">
                  <ul class="content-item-filter item-filter-radio">
                    @foreach($actions as $key => $action)
                    @if ($action->type == "filter_status")
                    <li class="checkbox_acount">
                      <input class="filter-filter_status-js" type="checkbox" value="{{$action->_id}}" id="{{$action->_id}}" name="filter_status">
                      <label class="label-checkbox" for="{{$action->_id}}">{{$action->text}}</label>
                    </li>
                    @endif
                    @endforeach
                  </ul>
                </div>
              </div>
              <div class="filter-item w-20">
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
              <div class="filter-item w-20">
                <div class="title text-center">
                  <i class="fal fa-file-check mr-1"></i>
                  <span class="text">Vận đơn & Kế toán</span>
                </div>
                <div class="item-search item-search-ship">
                  <ul class="content-item-filter item-filter-radio">
                    <li class="checkbox_acount">
                      <input class="filter_confirm-js" type="checkbox" value="null" id="null" name="filter_confirm">
                      <label class="label-checkbox" for="null">Đơn chưa xác nhận</label>
                    </li>
                    @foreach($actions as $key => $action)
                    @if ($action->type == "filter_confirm")
                    <li class="checkbox_acount">
                      <input class="filter-filter_status-js" type="checkbox" value="{{$action->_id}}" id="{{$action->_id}}" name="filter_status">
                      <label class="label-checkbox" for="{{$action->_id}}">{{$action->text}}</label>
                    </li>
                    @endif
                    @endforeach
                  </ul>
                </div>
                <div class="title text-center">
                  <i class="fal fa-user mr-1"></i>
                  <span class="text">lọc nhân viên</span>
                </div>
                <div class="item-search item-search-time h-40">
                  <ul class="content-item-filter item-filter-radio m-0">
                    <li class="checkbox_acount w-100 p-0">
                      <select name="user_id" class="input-control h-30 filter-user_id-js" id="">
                        <option selected value="-1">Tất cả</option>
                        @foreach($userCompany as $customer)
                        <option value="{{$customer->_id}}">{{$customer->username}}</option>
                        @endforeach
                      </select>
                    </li>
                  </ul>
                </div>
              </div>
              {{--<div class="filter-item w-15">
                <div class="title text-center">
                  <i class="fal fa-filter mr-1"></i>
                  <span class="text">Trạng thái đơn</span>
                </div>
                <div class="item-search item-search-ship">
                  <ul class="content-item-filter item-filter-radio">
                    
                    <li class="checkbox_acount">
                      <input class="filter-reason-js" type="checkbox" value="-1" id="reason-1" name="reason[]">
                      <label class="label-checkbox" for="reason-1">Đơn hàng mới</label>
                    </li>
                    <li class="checkbox_acount">
                      <input class="filter-reason-js" type="checkbox" value="success" id="reasonsuccess" name="reason[]">
                      <label class="label-checkbox" for="reasonsuccess">Đơn đã chốt</label>
                    </li>
                    <li class="checkbox_acount">
                      <input class="filter-reason-js" type="checkbox" value="wait" id="reasonwait" name="reason[]">
                      <label class="label-checkbox" for="reasonwait">Chưa chốt được</label>
                    </li>
                    <li class="checkbox_acount">
                      <input class="filter-reason-js" type="checkbox" value="cancel" id="reasoncancel" name="reason[]">
                      <label class="label-checkbox" for="reasoncancel">Đơn huỷ</label>
                    </li>
                  </ul>
                </div>
                <div class="title text-center">
                  <i class="fal fa-database mr-1"></i>
                  <span class="text">Nguồn đơn từ công ty</span>
                </div>
                <div class="item-search item-search-time h-40">
                  <ul class="content-item-filter item-filter-radio m-0">
                    <li class="checkbox_acount w-100 p-0">
                      <select name="company_mkt_id" class="input-control h-30 filter-company_mkt_id-js" id="">
                        <option selected value="-1">Tất cả</option>
                        @if ($user->type_account == "sale")
                        @foreach($companyConnect as $company)
                        <option value="{{$company->companyMkt->_id}}">{{$company->companyMkt->company_name}}</option>
              @endforeach
              @endif
              </select>
              </li>
              </ul>
            </div>
          </div>
          --}}
        </div>
      </div>
    </div>

  </div>
  <div class="row mt-3 flex-1">
    <div class="col-12 h-100">
      <div class="base-table-content base-table-layout flex flex-column" style="border-top: 1px solid #ddd">
        <div class="base-table--filter">
          <div class="panel-heading-left panel-heading-title" id="panel-heading-title">
            <h6 class="panel-title mb-1">
              <strong>Danh sách đơn hàng</strong>
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
          <div class="panel-heading-left d-flex align-items-center form-group m-0 search h-100">
            <div class="btn btn-info fs-14 pointer btn-click-edit-modal-js"><i class="fal fa-calendar-check mr-1"></i>Thao tác với đơn hàng được chọn</div>
          </div>
          <div class="panel-heading-left d-flex align-items-center form-group m-0 search h-100">
            <div class="btn btn-success fs-14 pointer btn-click-export-excel-js"><i class="fal fa-file-excel mr-1"></i>Xuất Excel</div>
          </div>
        </div>
        <div class="base-table--data flex-1 overflow-auto">
          <table id="headerTable" class="table-filter w-100">
            <thead>
              <tr class="text-center merge">
                <th></th>
                <th colspan="3">Thông tin khách hàng</th>
                <th colspan="4">Vận đơn</th>
                <th colspan="4">Thông tin</th>
              </tr>
              <tr class="sub-header">
                <th>
                  <div class="th-container" style="top: 0px; border-bottom: 0px;">
                    <li class="checkbox_acount list-item-order">
                      <input class="d-none stt-order-all-js" type="checkbox" id="stt-list-order-all">
                      <label class="label-checkbox base-table-item--checkbox" for="stt-list-order-all"></label>
                    </li>
                  </div>
                </th>
                <th data-index="0" class="th-typeview-name view-medium">
                  <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Tên khách hàng</span></div>
                </th>
                <th data-index="1" class="th-typeview-phones">
                  <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Số điện thoại</span></div>
                </th>
                <th data-index="1" class="th-typeview-phones view-large white-space-normal">
                  <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Địa chỉ giao hàng</span></div>
                </th>

                <th data-index="4" class="th-typeview-sale">
                  <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Ghi chú</span></div>
                </th>
                <th data-index="0" class="th-typeview-value">
                  <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Trạng thái đơn hàng</span></div>
                </th>
                <th data-index="4" class="th-typeview-sale">
                  <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Đơn từ sale</span></div>
                </th>
                <th data-index="5" class="th-typeview-san_pham_quan_tam">
                  <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Sản phẩm</span></div>
                </th>
                <th data-index="5" class="th-typeview-san_pham_quan_tam">
                  <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Thành tiền</span></div>
                </th>

                <th data-index="0" class="th-typeview-value">
                  <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Ngày chốt đơn</span></div>
                </th>
                <th data-index="0" class="th-typeview-value">
                  <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Ngày chuyển trạng thái</span></div>
                </th>
                <th data-index="0" class="th-typeview-value">
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
              <span class="mr-2">
                Số bản ghi / trang
                <select class="ml-2" style="height: 30px; width: 50px" name="count_page" id="count_page" class="mx-1">
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                  <option value="200">200</option>
                </select>
              </span>
              <span class="break-right"></span>
              <span class="ml-2 bold co-red fs-16" id="sumTotalDone"></span>
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
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content overflow-hidden">
      <div class="modal-body p-0">
        <form class="base-table--data">
          <div class="row">
            <div class="col-12">
              <div class="order-detail__title"><i class="fal fa-info-circle mr-1"></i>Chọn tình trạng đơn hàng</div>
            </div>
            <div class="col-12 filter-item h-auto" style="min-height: unset; max-height: unset">
              <ul class="content-item-filter item-filter-radio p-3 px-4">
                @foreach($actions as $key => $action)
                @if ($action->type == "filter_status")
                <li class="checkbox_acount col-3 mb-2">
                  <input class="custom-css" type="radio" value="{{$action->_id}}" id="modal-{{$action->_id}}" name="filter_status">
                  <label class="label-checkbox" for="modal-{{$action->_id}}">{{$action->text}}</label>
                </li>
                @endif
                @endforeach
              </ul>
              <div class="break"></div>
              <div class="order-detail__title"><i class="fal fa-info-circle mr-1"></i>Chọn đơn vị vận chuyển</div>
              <ul class="content-item-filter item-filter-radio p-3 px-4">
                @foreach($actions as $key => $action)
                @if ($action->type == "filter_ship")
                <li class="checkbox_acount col-3 mb-2">
                  <input class="custom-css" type="radio" value="{{$action->_id}}" id="modal-{{$action->_id}}" name="filter_ship">
                  <label class="label-checkbox" for="modal-{{$action->_id}}">{{$action->text}}</label>
                </li>
                @endif
                @endforeach
              </ul>
              <div class="break"></div>
              <div class="order-detail__title"><i class="fal fa-info-circle mr-1"></i>Vận đơn xác nhận</div>
              <ul class="content-item-filter item-filter-radio p-3 px-4">
                @foreach($actions as $key => $action)
                @if ($action->type == "filter_confirm")
                <li class="checkbox_acount col-3 mb-2">
                  <input class="custom-css" type="radio" value="{{$action->_id}}" id="modal-{{$action->_id}}" name="filter_confirm">
                  <label class="label-checkbox" for="modal-{{$action->_id}}">{{$action->text}}</label>
                </li>
                @endif
                @endforeach
              </ul>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        <button type="button" data-id="" class="btn btn-info btn-click-save-modal-js">Chuyển trạng thái</button>
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
      activity.setTotalDone(res.data.total);
    },
    getData: function() {
      loading.order.show('.base-table-content');
      api.getOrderList(activity.showDataListOrder);
    },
    setForm: function() {
      const params = new URLSearchParams(window.location.search);
      let paramObj = {};
      if (!!!window.location.search.includes('type_confirm_text')) {
        $('[name="filter_date"]').val(['date_success_order']);
        lib.updateParams('filter_date_by', 'date_success_order');
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
        } else if (key == "time_start" || key == "time_end" || key == "search") {
          $('[name="' + key + '"]').val(value);
        } else if (key == "type_confirm_text") {
          $('[name="filter_date"]').val([value]);
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
    },
    setTotalDone: function(data) {
      $('#sumTotalDone').html("Tổng thành tiền đơn đã chốt: " + format_curency(data.toString()) + "đ");
    }
  }

  const html = {
    renderList: function(res, item) {
      userReciver = product = '';
      if (item.order.reciver) {
        userReciver = '<span class="d-inline-block mb-1 user--reciver mr-2">' + item.order.reciver.name + '<i class="fas fa-star ml-1 fs-12 text-warning"></i></span>';
      }
      date = '';
      var detail = '{{ route("site.order.detail", ":id") }}' + window.location.search;
      detail = detail.replace(':id', item.order._id);
      try {

        if (item.order.filter_confirm.type_confirm == 0) {
          filter = "<span class='d-inline-block text-center alert-info reason-style'>" + item.order.filter_confirm.text + "</span>";
        } else if (item.order.filter_confirm.type_confirm == 1) {
          filter = "<span class='d-inline-block text-center alert-success reason-style'>" + item.order.filter_confirm.text + "</span>";
        } else if (item.order.filter_confirm.type_confirm == 2) {
          filter = "<span class='d-inline-block text-center alert-primary reason-style'>" + item.order.filter_confirm.text + "</span>";
        } else if (item.order.filter_confirm.type_confirm == 3) {
          filter = "<span class='d-inline-block text-center alert-warning reason-style'>" + item.order.filter_confirm.text + "</span>";
        } else {
          filter = "<span class='d-inline-block text-center alert-danger reason-style'>" + item.order.filter_confirm.text + "</span>";
        }
      } catch (error) {
        filter = "<span class='d-inline-block text-center alert-danger reason-style'>Đơn chưa xác nhận</span>";
      }

      if (item.address) {
        address = item.address;
      } else {
        address = ``;
      }

      try {
        shipNote = "";
        $.each(item.order.activity_landing, function(index, val) {
          if (!!val.origin_note && val.origin_note !== null) {
            shipNote = val.origin_note;
            return false;
          }
        });
      } catch (error) {
        shipNote = '';
      }

      if (item.proccessing) {
        dataHtml = `<tr data-id="` + item.order._id + `" class="table-proccessing">`;
      } else {
        dataHtml = `<tr data-id="` + item.order._id + `">`;
      }

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
      product = note = dateConfirm = '';
      arrProductExist = arrListProduct = [];
      // console.log();
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
        product += '<span class="bg-info mb-1 d-inline-block co-white text-center p-1 px-2 mx-2" style="border-radius: 10px">' + arrListProduct[productItem].amount + ' ' + arrListProduct[productItem].name + '</span>';
      });
      product = product.substring(0, product.length - 2);


      if (!!item.activity_landing && !!item.activity_landing.origin_note) {
        note = item.activity_landing.origin_note;
      }
      if (!!item.order.date_confirm) {
        dateConfirm = item.order.date_confirm;
      }

      dataHtml += `
            <td class="td-first">
              <li class="checkbox_acount list-item-order">
                <input class="d-none stt-order-js" type="checkbox" id="` + item._id + `">
                <label class="label-checkbox base-table-item--checkbox" for="` + item._id + `"></label>
              </li>
            </td>
            <td class="cell-hover-border td-typeview-name view-small white-space-normal">
                <div class="base_field_name"><a target="_blank" href="` + detail + `" title="` + item.name + `" class="detail-js a_overflow_hidden">` + item.name + `</a></div>
            </td>
            <td class="cell-hover-border td-typeview-phones">
                <div class="base_field_phones"><span>` + item.phone + `</span></div>
            </td>
            
            <td class="cell-hover-border td-typeview-phones view-medium white-space-normal">
                <div class="base_field_phones"><span>` + address + `</span></div>
            </td>
            <td class="cell-hover-border td-typeview-sale co-red bold view-medium white-space-normal">
                <div class="base_field_phones"><span>` + shipNote + `</span></div>
            </td>
            <td class="td-number">
              <span>` + filter + `</span>
            </td>
            <td class="cell-hover-border">
                <div class="base_field_phones"><span>` + userReciver + `</span></div>
            </td>
            
            <td class="cell-hover-border td-typeview-sale ">
                ` + product + `
            </td>
            <td class="cell-hover-border td-typeview-sale co-red bold">
                ` + format_curency(item.total) + ` VNĐ
            </td>
            <td class="td-number">
              <span>` + item.date_success_order + `</span>
            </td>
            <td class="td-number">
              <span>` + dateConfirm + `</span>
            </td>
            <td class="td-number text-center">
              <span><a target="_blank" href="` + detail + `"><i class="fal fa-edit"></i></a></span>
            </td>
            
        </tr>`;
      return dataHtml;
    },
    emptyData: function() {
      return '<td class="text-center p-3 bold fs-18" colspan="11">Không có dữ liệu khách hàng nào</td>';
    }
  }
  $(function() {

    $('.filter-reason-js').click(function() {
      self = $(this);
      if (self.is(":checked")) {
        lib.updateParams('reason[]', self.val());
      } else {
        lib.removeParams('reason[]', self.val());
      }
      activity.getData();
    });

    $('.filter-filter_status-js').click(function() {
      self = $(this);
      if (self.is(":checked")) {
        lib.updateParams('filter_status[]', self.val());
      } else {
        lib.removeParams('filter_status[]', self.val());
      }
      activity.getData();
    });


    $('.filter_confirm-js').click(function() {
      self = $(this);
      if (self.is(":checked")) {
        lib.updateParams('filter_confirm[]', self.val());
      } else {
        lib.removeParams('filter_confirm[]', self.val());
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

    $('.inp-data-user-reciver-js').click(function() {
      self = $(this);
      list = self.closest('.form-group').find('.assign-order-js');
      list.removeClass('d-none');
      list.find('.inp-find-user-js').focus();
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

    $(document).on('click', '.list-source--item-js', function(event) {
      event.preventDefault();

      $('.list-source--item').removeClass('active');
      $(this).addClass('active');

      var source = $(this).data('source');

      lib.updateParams('source_id', source);
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
    $(document).on('click', '[name="filter_date"]', function() {
      val = $('[name="filter_date"]:checked').val();
      if (val == "date_success_order") {
        lib.updateParams('filter_date_by', val);
        lib.removeParams('type_confirm_text');
      } else {
        lib.updateParams('filter_date_by', 'date_confirm');
        lib.updateParams('type_confirm_text', val);
      }


      activity.getData();
    });

    $(document).on('dblclick', '.base-table--data tbody tr', function() {
      window.open($(this).find('.detail-js').attr('href'), '_blank');
    });

    $(document).on('click', '.btn-click-edit-modal-js', function() {
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
        Notify.show.error('Bạn cần chọn ít nhất 1 đơn hàng');
        return;
      }
      modal = $('#modal');
      modal.find('form')[0].reset();
      modal.modal();
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

    $(document).on('keyup', '.inp-find-item-order-js', function(){
      val = $(this).val();
      if (!!!val) {
        lib.removeParams('search');
        activity.setForm();
        activity.getData();
      }
    });

    $(document).on('click', '.btn-click-save-modal-js', function() {
      var order = $('#table-body-data tr');
      modal = $('#modal');
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
        Notify.show.error('Bạn cần chọn ít nhất 1 đơn hàng');
        return;
      }

      form = modal.find('form').serializeArray();

      params = {
        order_id: dataOrder,
        form: form
      }

      lib.send.post('{{route("api.lading.updateStatus")}}', function(res) {
        if (res.success) {
          Notify.show.success(res.msg);
          activity.getData();
          modal.modal('hide');
        } else {
          Notify.show.error(res.msg);
        }
      }, params);
    });

    $('.btn-click-remove-time-start-js').click(function(){
      $('input[name="time_start"]').val('');
      lib.removeParams('time_start');
      activity.getData();
    });

    $('.btn-click-remove-time-end-js').click(function(){
      $('input[name="time_end"]').val('');
      lib.removeParams('time_end');
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

      val = $('[name="filter_date"]:checked').val();
      if (val == "created_at") {
        lib.removeParams('type_confirm_text');
      } else {
        lib.updateParams('type_confirm_text', val);
      }
      activity.getData();
    });

    $('.btn-click-export-excel-js').click(function() {
      Notiflix.Loading.Pulse('Đang xử lý...');
      lib.send.get('{{route("api.order.export.excel.lading")}}' + window.location.search, function(res) {
        window.location = '{{route("api.order.export.excel.lading")}}' + window.location.search;
        Notiflix.Loading.Remove();
      });
    });


    activity.getData();
    activity.setForm();
  });
</script>
@endsection