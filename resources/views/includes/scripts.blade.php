<!-- Core -->
<script src="{{ asset('static/vendor/popperjs-2.9.3/popper.min.js') }}"></script>
<script src="{{ asset('static/vendor/bootstrap-5.0.2/bootstrap.bundle.min.js') }}"></script>

<!-- Onscreen JS -->
<script src="{{ asset('static/vendor/onscreen-1.4.0/on-screen.umd.min.js') }}"></script>

<!-- Smooth scroll -->
<script src="{{ asset('static/vendor/smooth-scroll-16.1.3/smooth-scroll.polyfills.min.js') }}"></script>

<!-- Sweet Alerts 2 -->
<script src="{{ asset('static/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

<!-- jQuery -->
<script src="{{ asset('static/vendor/jquery-3.6.0.min.js') }}"></script>

<!-- Simplebar -->
<script src="{{ asset('static/vendor/simplebar-5.3.4/simplebar.min.js') }}"></script>

<!-- Charts -->
{{-- <script src="{{ asset('static/vendor/chartist-0.11.4/chartist.min.js') }}"></script>
<script src="{{ asset('static/vendor/chartist-plugin-tooltips-0.0.17/chartist-plugin-tooltip.min.js') }}"></script> --}}

<!-- Volt JS -->
<script src="{{ asset('static/js/volt.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('static/js/main.js') }}"></script>

{{--
<!-- Debug -->
<script>
    var tmp = '{!! json_encode(session()->all()) !!}';
    console.log(JSON.parse(tmp));
    tmp = '{!! json_encode($errors->all()) !!}';
    console.log(JSON.parse(tmp));
    tmp = '{!! json_encode(request()->all()) !!}';
    console.log(JSON.parse(tmp));
    delete tmp;
</script>
--}}

{{-- all additional scripts (if any) will be loaded after the core js files --}}
@stack('scripts')

{{--
<!-- Slider -->
<script src="{{ asset('static/vendor/nouislider/nouislider.min.js') }}"></script>

<!-- Notyf -->
<script src="{{ asset('static/vendor/notyf/notyf.min.js') }}"></script>
--}}
