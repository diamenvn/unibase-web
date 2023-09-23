
<div class="form-group">
    <label>
        <span>{{$label ?? ""}}</span>
        @if (isset($require) && $require)
            <span class="co-red">*</span>
        @endif
    </label>
    <input @if (isset($require) && $require) required @endif type="file" name="{{$name}}" class="form-control">
</div>