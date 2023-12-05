<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script src="{{asset('assets/site/theme/js/axios.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script src="{{asset('assets/site/theme/js/notiflix-2.1.2.min.js')}}"></script>
<script src="{{asset('assets/site/theme/js/app.js')}}"></script>
<script src="{{asset('assets/site/theme/js/library_js.js?v=30')}}"></script>
<script type="text/javascript" src="https://www.jqueryscript.net/demo/AJAX-Autocomplete-Bootstrap-Select/dist/js/ajax-bootstrap-select.js"></script>
<!-- FILEPOND -->
<script src="{{asset('assets/vendor/filepond/filepond.min.js')}}"></script>
<script src="{{asset('assets/vendor/filepond/filepond-preview-image.min.js')}}"></script>
<script src="{{asset('assets/vendor/filepond/filepond-jquery.min.js')}}"></script>


<script>
    window.uploadURI = '{{route("api.upload")}}';
    window.ASSETS_BASE_URI = '{{asset("/assets/site/theme/images")}}/';
    window.event = {
        'callAjaxModal': '{{$callAjaxModal}}',
        'callAjaxReplaceContent': '{{$callAjaxReplaceContent}}'
    };

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