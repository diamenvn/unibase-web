<div class="form-group">
    <label>
        <span>{{$label ?? ""}}</span>
        @if (isset($require) && $require)
        <span class="co-red">*</span>
        @endif
    </label>

    <div @if (isset($require) && $require) required @endif>
        <input type="file" accept="image/x-png,image/gif,image/jpeg" multiple="multiple" class="fs-12" name="{{$model}}"
            data-type="local"/>
    </div>

</div>
@section('custom_js')
<script>
    var images = @json($data[$model] ?? []);
    var path = @json(storage_path('app/'));
    FilePond.create(
        document.querySelector('input[type="file"]'),
        {
            "server": {
                "load": path
            },
            "files": Array.isArray(images) ? images.map(data => ({
                source: data,
                options: { "type": "local" }
            })) : [{
                source: images,
                options: { "type": "local" }
            }]
        }
    );
</script>
@endsection