<div class="order-list__item">
    <a href="{{route('site.order.detail', $order->_id)}}" class="d-block">
        <div class="order-list__item__title">
            <h5 class="fs-14 fs-600 m-0">#{{$order->order_number}} - {{$order->customer_1->name}}</h5>
        </div>
        <div class="order-list__item__desc">
            <span>{{$order->note}}</span>
        </div>
        <div class="order-list__item__footer">
            <div class="badgets my-2">
                @if (isset($order->order_type) && is_array($order->order_type))
                @foreach ($order->order_type as $badget)
                <div class="badget @if ($badget == 'facebook') badget--facebook @else badget--warning @endif">{{$badget}}</div>
                @endforeach
                @elseif (isset($order->order_type))
                <div class="badget badget--danger">{{$order->order_type}}</div>
                @endif
            </div>
            <div class="w-100 align-items-center d-flex">
                <a href="/" class="flex-1 d-flex align-items-center">
                    <div class="profile__avatar profile__avatar--sm p-0">
                        <img src="{{asset('assets/site/theme/images/gamer.png')}}" alt="" srcset="" />
                    </div>
                    <div class="profile__info">
                        <div class="profile__info__name mx-1 fs-12 co-default">
                            {{$order->customerCreateOrder->name}}
                        </div>
                    </div>
                </a>
                <span class="fs-10"><i class="fal fa-clock mx-2"></i>{{$order->created_at->format('d/m/Y')}}</span>
            </div>
        </div>
    </a>
</div>