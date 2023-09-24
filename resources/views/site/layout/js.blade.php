<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
<script src="{{asset('assets/site/theme/js/axios.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('assets/site/theme/js/notiflix-2.1.2.min.js')}}"></script>
<script src="{{asset('assets/site/theme/js/app.js')}}"></script>
<script src="{{asset('assets/site/theme/js/library_js.js?v=30')}}"></script>

<!-- FILEPOND -->
<script src="{{asset('assets/vendor/filepond/filepond.min.js')}}"></script>
<script src="{{asset('assets/vendor/filepond/filepond-preview-image.min.js')}}"></script>
<script src="{{asset('assets/vendor/filepond/filepond-jquery.min.js')}}"></script>


<script>
    window.uploadURI = '{{route("api.upload")}}';
    $(function () {
        $("input[data-type='currency']").simpleMoneyFormat();
        $.fn.filepond.setOptions({
            server: {
                process: function process(fieldName, file, metadata, load, error, progress, _abort, transfer, options) {
                    var formData = new FormData();
                    formData.append('file', file, file.name);
                    var request = new XMLHttpRequest();
                    request.open('POST', window.uploadURI);
                    request.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').getAttribute("content"));

                    request.onload = function () {
                        if (request.status >= 200 && request.status < 300) {
                            load(JSON.parse(request.responseText).data);
                        } else {
                            error('Error');
                        }
                    };

                    request.send(formData); // Should expose an abort method so the request can be cancelled

                    return {
                        abort: function abort() {
                            // This function is entered if the user has tapped the cancel button
                            request.abort(); // Let FilePond know the request has been cancelled

                            _abort();
                        }
                    };
                }
            }
        });
    })
</script>