@foreach($columns as $index => $column)
<div data-column-id="{{$column->_id}}" class="col order-list__columns">
    <div class="order-list__head" style='background: {{$column->styles["background"] ?? ""}}; color: {{$column->styles["color"] ?? ""}}'>
        <div class="d-flex align-items-center my-1">
            <div class="flex-1">
                <h4 class="title m-0 fs-16 text-one-line">{{$index + 1}}. {{$column->label_name}}</h4>
            </div>
        </div>
        <div class="d-flex">
            <div class="w-100">
                <div class="progress progress--success my-2">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:60%"></div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between my-1">
            <div class="left">
                <i class="fal fa-alarm-clock"></i>
                <span class="color-default">0 quá hạn</span>
            </div>
            <div class="right">
                <i class="fal fa-truck"></i>
                <span class="color-default">{{count($column->order)}} đơn hàng</span>
            </div>
        </div>
    </div>
    <div data-element="body" class="order-list__body">

    </div>
</div>
@endforeach