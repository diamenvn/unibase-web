@extends('site.layout.master')
@section('title', 'Thêm sản phẩm')

@section('content')
<div class="app-content">
    <div class="section">
        <div class="main-body flex flex-column">
            <div class="row setting-group setting-group--layout h-100">
                <div class="col-4 col--custom setting-group__item d-flex flex-column h-100">
                    <div class="group__title">Thêm sản phẩm</div>
                    <div class="group__body flex-1 overflow-x-none overflow-y-auto bg-white">
                        <div class="row p-3">
                            <div class="col-12 mb-3">
                                <form class="form form-js">
                                    <div class="form-group">
                                        <label for="productName">Tên sản phẩm</label>
                                        <input type="text" name="product_name" class="form-control" id="productName" placeholder="Nhập tên sản phẩm">
                                    </div>
                                    <div class="form-group">
                                        <label for="productPrice">Giá bán 1 sản phẩm</label>
                                        <input type="text" data-type="currency" onkeypress='number(event)' name="price" class="form-control" id="productPrice" placeholder="Giá sản phẩm">
                                    </div>
                                    @if ($companyType == "mkt")
                                        <div class="form-group">
                                            <label for="companyReciver">Công ty nhận số</label>
                                            <select name="company_sale_id" class="form-control" id="companyReciver">
                                                @foreach($connect as $item)
                                                    <option value="{{$item->companySale->_id}}">{{$item->companySale->company_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </form>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="btn btn-add-group d-block co-white cursor-pointer bg-green btn-save-js">Thêm sản phẩm</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8 col--custom setting-group__item d-flex flex-column h-100" id="members">
                    <div class="group__title">Danh sách sản phẩm hiện tại</div>
                    <div class="group__body flex-1 overflow-x-none overflow-y-auto bg-white">
                        <div class="row p-3">
                            @if (!empty($product))
                            <form id="form-update" class="col-12">
                                <table class="table table__list">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Tên sản phẩm</th>
                                            @if ($companyType == "mkt")
                                                <th>Công ty nhận số</th>
                                            @endif
                                            
                                            <th class="text-center">Giá bán</th>
                                            <th>Ngày tạo</th>
                                            <th class="text-center">Xoá</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list-member-js">
                                        @php $key = 0; @endphp
                                        @foreach($product as $item)
                                        @php $key += 1; @endphp
                                        <tr>
                                            <td>{{$key}}</td>
                                            <td>{{$item->product->product_name}}</td>
                                            @if ($companyType == "mkt")
                                                <td>{{$item->connect->companySale->company_name}}</td>
                                            @endif
                                            <td class="text-center">{{number_format($item->product->price)}}đ</td>
                                            <td>{{$item->created_at}}</td>
                                            <td class="text-center"><i data-href="{{route('api.setting.hiddenProduct', ['id' => $item->_id])}}" class="fal fa-trash-alt click-remove-ajax"></i></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </form>
                            @else
                            <h5 class="px-3">Chưa có sản phẩm nào</h5>
                            @endif
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
    $(function() {
        $('.btn-add-member-js').click(function() {
            html = render.list();
            $('#list-member-js').append(html);
        });

        $('.btn-save-js').click(function() {
            hasContinue = true;
            form = $('.form-js').serialize();
            inputNames = ['product_name', 'price'];

            $.each(inputNames, function(index, val){
                item = $('*[name="' + val + '"]');
                if (!!!item.val()) {
                    item.addClass('invalid');
                    hasContinue = false;
                } else {
                    item.removeClass('invalid');
                }
            });
            if (!hasContinue) {
                Notify.show.error('Vui lòng điền đầy đủ thông tin');
                return;
            }

            Notiflix.Loading.Circle('Đang tạo dữ liệu...');
            lib.send.post('{{route("api.setting.saveAddProduct")}}', function(res) {
                if (res.success){
                    Notify.show.success(res.msg);
                    setTimeout(function(){
                        window.location.reload();
                    }, 1000);
                }else{
                    Notify.show.error(res.msg);
                }
                Notiflix.Loading.Remove();
            }, form);
        });

        $('.btn-create-group-js').click(function() {
            input = $('#input_group_name');
            error = $('.invalid-error');
            if (!!!input.val()) {
                input.addClass('invalid');
                error.show();
                return;
            } else {
                input.removeClass('invalid');
                error.hide();
            }
            data = {
                'title': input.val()
            };
            lib.send.post('{{route("api.setting.createGroup")}}', function(res) {
                if (res.success){
                    Notify.show.success(res.msg);
                    window.location.href = res.data.url;
                }else{
                    Notify.show.error(res.msg);
                }
                Notiflix.Loading.Remove();
            }, data);
        });

    })
</script>
@endsection