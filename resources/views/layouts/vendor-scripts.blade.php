<script type="text/javascript" src="{{ URL::asset('js/libs/jquery-3.7.1.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/@popperjs/core/umd/popper.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/metismenujs/metismenujs.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    var searchUrl = "{{ env('APP_URL') }}";

    var auth = "{{Auth::user()}}";
    var is_admin = "{{getCurrentUserRoleName()}}";
</script>
@yield('scripts')