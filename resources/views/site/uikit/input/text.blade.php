<div class="form-group">
    <label>
        <span>{{$label ?? ""}}</span>
        @if (isset($require) && $require)
            <span class="co-red">*</span>
        @endif
    </label>
    <input @if (isset($require) && $require) required @endif  class="form-control bg-white" name="{{$name}}" type="text" placeholder="{{$placeholder ?? ''}}"
        value="{{$value ?? ''}}">
</div>