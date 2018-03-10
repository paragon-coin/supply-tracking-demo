@if((!empty($errors) && $errors->any()) || session()->has('message') || session()->has('status') || session()->has('info') || session()->has('error'))
    @push('scripts')
        <script>
            $(function () {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "progressBar": true,
                    "newestOnTop": false,
                    "preventDuplicates": false,
                    "positionClass": "toast-top-right",
                    "onclick": null,
                    "showDuration": "400",
                    "hideDuration": "2000",
                    "timeOut": "7000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };
                @if(!empty($errors) && $errors->any())
                    @foreach($errors->all() as $error)
                        toastr.error("{!! $error !!}");
                    @endforeach
                @endif

                @if (session()->has('error'))
                    toastr.error('{!! session('error') !!}');
                @endif

                @if (session()->has('message'))
                    toastr.success('{!! session('message') !!}');
                @endif

                @if (session()->has('status'))
                    toastr.success('{!! session('status') !!}');
                @endif

                @if (session()->has('info'))
                    toastr.info("{!! session('info') !!}");
                @endif
            });
        </script>
    @endpush
@endif