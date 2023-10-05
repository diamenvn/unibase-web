<div class="order-list__item">
    <a href="{{route('site.order.detail', $order->_id)}}" class="d-block">
        <div class="order-list__item__title">
            <h5 class="fs-14 fs-600 m-0">#00125 - Mua phần mềm Unibase</h5>
        </div>
        <div class="order-list__item__desc">
            <span>Nước ngập nửa thân ôtô, giao thông hỗn loạn, một số tuyến đường chính ở New York bị phong tỏa sau khi mưa lớn trút xuống vùng đông bắc nước Mỹ</span>
        </div>
        <div class="order-list__item__footer">
            <div class="badgets my-2">
                <div class="badget badget--danger">quantrong</div>
                <div class="badget badget--warning">hoatoc</div>
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