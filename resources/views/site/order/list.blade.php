@extends('site.layout.master', ['useTopHeader' => 'light'])
@section('title', 'Danh sách đơn hàng')
@section('page-title', 'B2B - Quản trị đơn hàng')

@section('page-navigate')
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

@section('content')
<div class="app-content">
  <div class="section">
    <div class="main-body flex flex-column">
      <div class="source-filter source-filter--layout">
        <ul class="list-source nav nav-tabs border-none">
          <li class="list-source--item list-source--item-js active">
            <a href="#">
              <i class="fal fa-list-ol"></i>
              <strong>Tất cả</strong>
            </a>
          </li>
          @foreach($sources as $item)
          <li class="list-source--item list-source--item-js" data-source="{{$item->source_id}}">
            <a href="javascript:void(0)">
              <i class="fal fa-filter"></i>
              <strong>{{$item->source->source_name}}</strong>
            </a>
          </li>
          @endforeach
        </ul>
      </div>
      <div class="row flex-1">
        <div class="col-12 h-100">
          <div class="base-table-content base-table-layout flex flex-column">
            <div class="base-table--filter d-flex justify-content-between">
              <div class="flex-left d-flex">
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
                @if (($user->permission == "admin" && $user->type_account == "sale") || ($user->assign_order == true))
                <div class="panel-heading-left d-flex align-items-center form-group m-0 search h-100">
                  <div class="input-group">
                    <input type="text" readonly class="pointer outline-none form-control fs-14 inp-data-user-reciver-js" placeholder="Chọn người nhận đơn" id="inp-data-user-reciver-js">
                    <div class="input-group-append">
                      <button class="btn btn-primary fs-14 btn-data-user-reciver-js" style="border-top-right-radius: .25rem; border-bottom-right-radius: .25rem" type="button">Gán
                        số</button>
                    </div>
                    <div class="assign-order-js assign-order--layout position-absolute d-none bg-white form-group">
                      <div class="row p-2">
                        <div class="col-12">
                          <input type="text" class="form-control fs-14 inp-find-user-js" placeholder="Nhập tên, tài khoản tìm">
                        </div>
                      </div>
                      <ul class="list">
                        @foreach($info as $customer)
                        <li data-selected="false" class="d-flex justify-content-between list-item-js" data-uid="{{$customer->_id}}">
                          <span class="name">{{$customer->username}}</span>
                          <span class="icon-check co-green"><i class="fal fa-check"></i></span>
                        </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
                @endif
              </div>
              <div class="flex-right">
                @if ($user->permission == "admin")
                <div class="btn btn-danger fs-13 btn-click-remove-js pointer"><i class="fal fa-trash-alt mr-1"></i>Xoá
                  đơn</div>
                @endif
                @if ($user->type_account == "mkt")
                <div class="btn btn-info fs-13 pointer"> <a href="{{route('site.order.create')}}"> <i class="fal fa-plus mr-1"></i>Tạo đơn mới</a> </div>
                @endif
              </div>
            </div>
            <div class="base-table--data flex-1 overflow-auto">
              <table id="headerTable" class="table-filter w-100">
                <thead>
                  <tr class="text-center merge">
                    <th></th>
                    <th colspan="3">Thông tin khách hàng</th>
                    <th colspan="1">Nhân sự phụ trách</th>
                    <th colspan="1">Phân loại</th>
                    <th colspan="2">Nguồn Khách hàng</th>
                    <th colspan="5">Thông tin</th>
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
                    <th data-index="0" class="th-typeview-name">
                      <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Tên khách hàng</span></div>
                    </th>
                    <th data-index="1" class="th-typeview-phones">
                      <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Số điện thoại</span></div>
                    </th>
                    <th data-index="3" class="th-typeview-ngay_lead_chuyen_sale">
                      <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Ngày sale nhận đơn</span></div>
                    </th>
                    <th data-index="4" class="th-typeview-sale">
                      <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Nhân sự phụ trách</span></div>
                    </th>
                    <th data-index="5" class="th-typeview-san_pham_quan_tam">
                      <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Sản phẩm quan tâm</span></div>
                    </th>
                    <th data-index="0" class="th-typeview-value">
                      <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">MKT tạo đơn</span></div>
                    </th>
                    <th data-index="0" class="th-typeview-value">
                      <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Nguồn</span></div>
                    </th>
                    <th data-index="0" class="th-typeview-value">
                      <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Trạng thái</span></div>
                    </th>
                    <th data-index="0" class="th-typeview-value">
                      <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Tình trạng khách hàng</span></div>
                    </th>
                    <th data-index="0" class="th-typeview-value">
                      <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Ghi chú</span></div>
                    </th>
                    <th data-index="0" class="th-typeview-value">
                      <div class="th-container" style="top: 0px; border-bottom: 0px;"><span class="vg-label fix_width" style="">Ngày tạo đơn</span></div>
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
    getData: function () {
      loading.order.show('.base-table-content');
      api.getLabelList(function () {
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

      // if (!!!window.location.search.includes('reason') && !!!window.location.search.includes('user_reciver_id') && !!!window.location.search.includes('search')) {
      //   lib.updateParams('reason[]', '-1');
      //   $('#reason-1').prop('checked', true);
      // }

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
    renderList: function (res, item) {
      userReciver = userCreated = filterStatus = '';
      if (item.reciver) {
        userReciver = '<span class="d-inline-block mb-1 user--reciver mr-2">' + item.reciver.name + '<i class="fas fa-star ml-1 fs-12 text-warning"></i></span>';
      }
      if (item.customer_create_order) {
        userCreated = '<span class="d-inline-block mb-1 user--reciver mr-2">' + item.customer_create_order.name + '</span>';
      }
      date = source = note = '';
      var detail = '{{ route("site.order.detail", ":id") }}';
      detail = detail.replace(':id', item._id);

      reason = {
        'wait': "<span class='alert-warning reason-style'>Chưa chốt được</span>",
        'success': "<span class='alert-success reason-style'>Đơn đã chốt</span>",
        'cancel': "<span class='alert-danger reason-style'>Đơn huỷ</span>",
        'undefined': "<span class='alert-primary reason-style'>Đơn hàng mới</span>"
      }

      if (item.proccessing) {
        dataHtml = `<tr data-id="` + item._id + `" class="table-proccessing">`
      } else {
        dataHtml = `<tr data-id="` + item._id + `">`
      }

      if (item.source) {
        source = item.source.source_name;
      }

      phoneExist = phoneExistItem = '';
      if (item.order.length > 1) {
        item.order.forEach(order => {
          if (order._id !== item._id) {
            phoneExistItem += `<li><i class="fas fa-user mr-2"></i>` + order.name + `</li>`;
          }
        });
        phoneExist = `<span class="position-relative item-phone-exist d-inline-block mb-1 user--reciver px-2 ml-2">
                    ` + parseInt(item.order.length - 1) + `
                    <i class="fas fa-chevron-down ml-1 fs-12"></i>
                    <div class="list-phone-exist fs-13 position-absolute bg-white">
                      <span class="title mb-2">Số trùng với ` + parseInt(item.order.length - 1) + ` khách hàng</span>
                      <ul class="m-0">` + phoneExistItem + `</ul>
                    </div>
                  </span>`;
      }
      if (item.date_reciver !== undefined) {
        date = item.date_reciver;
      }
      if (!!item.filter_status) {
        filterStatus = item.filter_status.text;
      }

      loop = false;
      if (item.activity.length > 0) {
        item.activity.forEach(function (value) {
          if (typeof (value.origin_note) == 'string') {
            if (loop) { return; }
            note = value.origin_note;
            loop = true;
          }
        });
      }


      dataHtml += `
            <td class="td-first">
              <li class="checkbox_acount list-item-order">
                <input class="d-none stt-order-js" type="checkbox" id="` + item._id + `">
                <label class="label-checkbox base-table-item--checkbox" for="` + item._id + `"></label>
              </li>
            </td>
            <td class="cell-hover-border td-typeview-name view-medium white-space-normal">
                <div class="base_field_name"><a class="detail-js" href="` + detail + `" title="` + item.name + `" class="a_overflow_hidden">` + item.name + `</a></div>
            </td>
            <td class="cell-hover-border td-typeview-phones ">
                <div class="base_field_phones"><span>` + item.phone + phoneExist + `</div>
            </td>
            <td class="cell-hover-border td-typeview-ngay_lead_chuyen_sale ">
                ` + date + `
            </td>
            <td class="cell-hover-border td-typeview-sale ">
                ` + userReciver + `
            </td>
            <td class="cell-hover-border td-typeview-san_pham_quan_tam ">
                <div class="js-value-container fix_width">` + item.product.product_name + `</div>
            </td>
            <td class="cell-hover-border td-typeview-value ">
              <div class="js-value-container fix_width">` + userCreated + `</div>
            </td>
            <td class="cell-hover-border td-typeview-value ">
              <div class="js-value-container fix_width">` + source + `</div>
            </td>
            <td class="td-number">
              <span>` + reason[item.reason] + `</span>
            </td>
            <td class="td-number">
              <span>` + filterStatus + `</span>
            </td>
            <td class="td-number">
              <span>` + note + `</span>
            </td>
            <td class="td-number">
              <span>` + item.created_at + `</span>
            </td>
            <td class="td-number text-center">
              <span><a href="` + detail + `"><i class="fal fa-edit"></i></a></span>
            </td>
            
        </tr>`;
      return dataHtml;
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