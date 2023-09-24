<div class="form-group">
    <label>
        <span>{{$label ?? ""}}</span>
        @if (isset($require) && $require)
            <span class="co-red">*</span>
        @endif
    </label>
    <div class="input-group">
        <input @if (isset($require) && $require) required @endif data-type="currency" data-type="currency" class="form-control bg-white price" onkeypress='number(event)' name="{{$model}}" type="text"
            placeholder="" value="{{$data[$model] ?? ''}}">
        <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2">đ</span>
        </div>
    </div>
</div>