@extends('site.layout.master')
@section('title', 'Dashboard')

@section('content')
<div class="app-content">
  <div class="section">

    <div class="main-body">
      <div class="row">
        <div class="col-xl-4">
          <div class="card overflow-hidden card--block-1">
            <div class="bg-soft-primary">
              <div class="row">
                <div class="col-7">
                  <div class="p-3 co-purple">
                    <h5 class="title">Xin chào {{$customer->name}}</h5>
                    <p>Công ty: {{$customer->company->company_name}}</p>
                  </div>
                </div>
                <div class="col-5 align-self-end">
                  <img src="{{asset('assets/site/theme/images/profile-img.png')}}" alt="" class="img-fluid">
                </div>
              </div>
            </div>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col-sm-4">
                  <div class="avatar-md profile-user-wid mb-4">
                    <img src="/assets/site/theme/images/logo.png" alt="" class="img-thumbnail rounded-circle">
                  </div>
                  <h5 class="font-size-15 text-truncate">{{$customer->name}}</h5>
                  <p class="text-muted mb-0 text-truncate">Quản lý chi nhánh</p>
                </div>

                <div class="col-sm-8">
                  <div class="pt-4">

                    <div class="row">
                      <div class="col-6">
                        <h5 class="font-size-15">125</h5>
                        <p class="text-muted mb-0">Khách hàng mới</p>
                      </div>
                      <div class="col-6">
                        <h5 class="font-size-15">6705</h5>
                        <p class="text-muted mb-0">Tổng khách hàng</p>
                      </div>
                    </div>
                    <div class="mt-4">
                      <a href="" class="btn btn-primary waves-effect waves-light btn-sm">Xem thông tin<i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="col-xl-8">
          <div class="row h-100">
            <div class="col-md-12 h-100">
              <div class="card mini-stats-wid h-100 base-table-content">
                <div class="row">
                  <div class="col-4">
                    <div class="card-body p-0">
                      <div class="media px-3 py-4 border-bottom">
                        <div class="media-body">
                          <h4 class="mt-0 mb-1 font-size-22 font-weight-normal orderNew">0</h4>
                          <span class="text-muted">Số đơn chưa xử lý</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag align-self-center icon-dual icon-lg">
                          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                          <line x1="3" y1="6" x2="21" y2="6"></line>
                          <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                      </div>

                      <!-- stat 2 -->
                      <div class="media px-3 py-4 border-bottom">
                        <div class="media-body co-green">
                          <h4 class="mt-0 mb-1 font-size-22 font-weight-normal orderSuccess">0</h4>
                          <span class="text-muted">Đơn đã chốt</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag align-self-center icon-dual icon-lg">
                          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                          <line x1="3" y1="6" x2="21" y2="6"></line>
                          <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                      </div>

                      <!-- stat 3 -->
                      <div class="media px-3 py-4">
                        <div class="media-body co-red">
                          <h4 class="mt-0 mb-1 font-size-22 font-weight-normal orderWait">0</h4>
                          <span class="text-muted">Đơn chưa chốt được</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag align-self-center icon-dual icon-lg">
                          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                          <line x1="3" y1="6" x2="21" y2="6"></line>
                          <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="card-body p-0">
                      <div class="media px-3 py-4 border-bottom">
                        <div class="media-body co-green">
                          <h4 class="mt-0 mb-1 font-size-22 font-weight-normal"><span class="orderSuccessToday">0</span>đ</h4>
                          <span class="text-muted">Doanh thu đơn chốt theo ngày</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag align-self-center icon-dual icon-lg">
                          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                          <line x1="3" y1="6" x2="21" y2="6"></line>
                          <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                      </div>

                      <!-- stat 2 -->
                      <div class="media px-3 py-4 border-bottom">
                        <div class="media-body co-purple">
                          <h4 class="mt-0 mb-1 font-size-22 font-weight-normal"><span class="orderConfirmToday">0</span>đ</h4>
                          <span class="text-muted">Doanh thu đơn xác nhận theo ngày</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag align-self-center icon-dual icon-lg">
                          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                          <line x1="3" y1="6" x2="21" y2="6"></line>
                          <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                      </div>

                      <!-- stat 3 -->
                      <div class="media px-3 py-4">
                        <div class="media-body co-red">
                          <h4 class="mt-0 mb-1 font-size-22 font-weight-normal"><span class="orderReturnToday">0</span>đ</h4>
                          <span class="text-muted">Số tiền đơn hoàn theo ngày</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag align-self-center icon-dual icon-lg">
                          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                          <line x1="3" y1="6" x2="21" y2="6"></line>
                          <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="card-body p-0">
                      <div class="media px-3 py-4 border-bottom">
                        <div class="media-body co-green">
                          <h4 class="mt-0 mb-1 font-size-22 font-weight-normal"><span class="orderSuccessMonth">0</span>đ</h4>
                          <span class="text-muted">Doanh thu đơn chốt theo tháng</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag align-self-center icon-dual icon-lg">
                          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                          <line x1="3" y1="6" x2="21" y2="6"></line>
                          <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                      </div>

                      <!-- stat 2 -->
                      <div class="media px-3 py-4 border-bottom">
                        <div class="media-body co-purple">
                          <h4 class="mt-0 mb-1 font-size-22 font-weight-normal"><span class="orderConfirmMonth">0</span>đ</h4>
                          <span class="text-muted">Doanh thu đơn xác nhận theo tháng</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag align-self-center icon-dual icon-lg">
                          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                          <line x1="3" y1="6" x2="21" y2="6"></line>
                          <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                      </div>

                      <!-- stat 3 -->
                      <div class="media px-3 py-4">
                        <div class="media-body co-red">
                          <h4 class="mt-0 mb-1 font-size-22 font-weight-normal"><span class="orderReturnMonth">0</span>đ</h4>
                          <span class="text-muted">Số tiền đơn hoàn theo tháng</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag align-self-center icon-dual icon-lg">
                          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                          <line x1="3" y1="6" x2="21" y2="6"></line>
                          <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-3 block-2">
        <div class="col-xl-4">
          <div class="card card--custom h-100">
            <div class="card-header flex-between">
              <h4 class="card-title float-left">Lịch sử ghi chú đơn hàng</h4>
              <button class="btn btn-outline-primary float-right fs-15">Xem tất cả</button>
            </div>
            <div class="card-body mix-scrollbar">
              <ol class="activity-feed">
                @if (!empty($activity))
                @foreach($activity as $item)
                <li class="feed-item">
                  <div class="feed-item-list">
                    <div class="d-flex"><span class="name">{{$item->customer->name}}</span><span class="date"> - {{$item->created_at}}</span></div> <span class="activity-text">{!! $item->note !!}</span>
                  </div>
                </li>
                @endforeach
                @endif
              </ol>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card card--custom">
            <div class="card-header flex-between">
              <h4 class="card-title float-left">Nguồn dữ liệu</h4>
              <button class="btn btn-outline-primary float-right fs-15">Xem tất cả</button>
            </div>
            <div class="card-body">
              <div id="ct-donut" class="ct-chart wid">
                <div class="chart3 flex flex-center"></div>
              </div>
              <div class="mt-4">
                <table class="table mb-0">
                  <tbody>
                    <tr>
                      <td><span class="badge badge-primary">Desk</span></td>
                      <td>Desktop</td>
                      <td class="text-right">54.5%</td>
                    </tr>
                    <tr>
                      <td><span class="badge badge-success">Mob</span></td>
                      <td>Mobile</td>
                      <td class="text-right">28.0%</td>
                    </tr>
                    <tr>
                      <td><span class="badge badge-warning">Tab</span></td>
                      <td>Tablets</td>
                      <td class="text-right">17.5%</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card card--custom h-100">
            <div class="card-header flex-between">
              <h4 class="card-title float-left">Khách hàng mới</h4>
              <button class="btn btn-outline-primary float-right fs-15">Xem tất cả</button>
            </div>
            <div class="card-body mix-scrollbar">
              <table class="table table-borderless table-striped dataTable table_1">
                <tbody>
                  @if (!empty($newCustomer))
                  @foreach($newCustomer as $customer)
                  <tr>
                    <td class="pr-1" style="color: rgb(116, 120, 141);">
                      <div><b><a href="javascript:void(0)">{{$customer->name}}</a></b></div>
                      <div>{{str_repeat("*", strlen($customer->phone) - (strlen($customer->phone) - 3)) . substr($customer->phone, 3, strlen($customer->phone))}}</div>
                      <div>{{$customer->address}}</div>
                    </td>
                    <td class="text-right pl-1" style="color: rgb(116, 120, 141);"><span><span>{{$customer->created_at}}</span></span></td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row my-3 block-6">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body">
              <div class="chart1"></div>
            </div>
          </div>
        </div>
      </div>
    </div>



  </div>
</div>
@endsection

@section('lib_js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section('custom_js')
<script>
  var options = {
    series: [{
      name: 'Data',
      data: [15, 10, 2, 30, 45, 22, 40, 17, 7, 10]
    }],
    chart: {
      height: '200px',
      type: "area",
      animations: {
        enabled: true
      },
      stacked: false,
    },
    dataLabels: {
      enabled: true
    },
    stroke: {
      colors: ["#fff"],
      curve: "straight",
      width: 3
    },
    grid: {
      borderColor: "rgba(255,225,255,0.9)",
      strokeDashArray: "3"
    },
    title: {
      text: 'Khách hàng phát sinh mới 7 ngày gần đây'
    },
    labels: ["10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ"],
    yaxis: {},
    legend: {
      horizontalAlign: "left"
    },
    tooltip: {
      shared: true,
      intersect: false,
      y: {
        formatter: function(y) {
          if (typeof y !== "undefined") {
            return y.toFixed(0) + " số";
          }
          return y;

        }
      }
    }
  };

  var options2 = {
    series: [{
      name: 'Data',
      data: [15, 10, 2, 30, 45, 22, 40, 17, 7, 10]
    }],
    chart: {
      height: '400px',
      type: "area",
      animations: {
        enabled: true
      },
      stacked: false,
    },
    dataLabels: {
      enabled: true
    },
    stroke: {
      colors: ["#fff"],
      curve: "straight",
      width: 3
    },
    grid: {
      borderColor: "rgba(255,225,255,0.9)",
      strokeDashArray: "3"
    },
    title: {
      text: 'Số nhận được trong 10 giờ vừa qua'
    },
    labels: ["10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ", "10 Giờ"],
    yaxis: {},
    legend: {
      horizontalAlign: "left"
    },
    tooltip: {
      shared: true,
      intersect: false,
      y: {
        formatter: function(y) {
          if (typeof y !== "undefined") {
            return y.toFixed(0) + " số";
          }
          return y;

        }
      }
    }
  };

  var optionsPie = {
    series: [44, 55, 13, 43, 22],
    chart: {
      width: 380,
      type: 'donut',
    },
    fill: {
      type: 'gradient',
    },
    legend: {
      formatter: function(val, opts) {
        return val + " - " + opts.w.globals.series[opts.seriesIndex]
      }
    },
    stroke: {
      width: 1
    },
    legend: {
      position: 'bottom'
    },
    labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
    responsive: [{
      breakpoint: 480,
      options: {
        chart: {
          width: 200
        },
        legend: {
          position: 'bottom'
        }
      }
    }]
  };
  var chart1 = new ApexCharts(document.querySelector(".chart1"), options2);
  chart1.render();
  var chart3 = new ApexCharts(document.querySelector(".chart3"), optionsPie);
  chart3.render();
</script>


<script>
  const api = {
    getOrderList: function(callback) {
      lib.send.get('{{route("api.home.report")}}', callback, window.location.search);
    }
  }

  const activity = {
    showDataListOrder: function(res) {
      if (res.success) {
        if (!!res.data) {
          for (var key in res.data) {
            $('.' + key).html(format_curency(res.data[key].toString()));
          }
        }

      }
      Notiflix.Block.Remove('.base-table-content');
    },
    getData: function() {
      loading.order.show('.base-table-content');
      api.getOrderList(activity.showDataListOrder);
    }
  }


  $(function() {
    activity.getData();
  });
</script>
@endsection