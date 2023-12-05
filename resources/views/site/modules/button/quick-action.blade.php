<div class="button-sticky-bottom">
    <div class="position-relative">
        <div class="button-sitcky-panel module">
            <ul class="list-group">
                <li v-click="{{$callAjaxModal}}" data-href="{{route('site.order.create')}}" class="list-group-item"><span>Tạo đơn hàng mới</span></li>
                <li v-click="{{$callAjaxModal}}" data-href="{{route('site.product.create')}}" class="list-group-item"><span>Tạo sản phẩm mới</span></li>
                <li v-click="{{$callAjaxModal}}" data-href="{{route('site.customer.create')}}" class="list-group-item"><span>Tạo khách hàng mới</span></li>
                {{-- <li class="list-group-item"><span>Import đơn hàng</span></li> --}}
                <li v-click="{{$callAjaxModal}}" data-href="{{route('site.store.form')}}" v-modal-align="center" width="600px" class="list-group-item"><span>Kết nối API với store</span></li>
              </ul>
        </div>
    </div>
    <div class="button-sticky-bottom-icon d-flex align-items-center justify-content-center fs-30">
        <i class="fas fa-plus"></i>
    </div>
</div>