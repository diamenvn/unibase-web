<div class="form-group">
    <label>{{$label}}</label>
    <div class="input-group">
        <input @if (isset($require) && $require) required @endif data-type="currency" data-type="currency" class="form-control bg-white price" onkeypress='number(event)' name="{{$name}}" type="text"
            placeholder="" value="">
        <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2">đ</span>
        </div>
    </div>
</div>