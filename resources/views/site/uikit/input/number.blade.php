<div class="form-group">
    <label>
        <span>{{$label ?? ""}}</span>
        @if (isset($require) && $require)
            <span class="co-red">*</span>
        @endif
    </label>
    <input @if (isset($require) && $require) required @endif data-type="currency" data-type="currency" class="form-control bg-white price" onkeypress='number(event)' class="form-control bg-white" name="{{$model}}" type="text" placeholder="{{$placeholder ?? ''}}"
        value="{{$data[$model] ?? ''}}">
</div>