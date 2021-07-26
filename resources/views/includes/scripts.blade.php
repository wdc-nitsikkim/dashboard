<!-- Core -->
<script src="{{ asset('static/vendor/@popperjs/core/dist/umd/popper.min.js') }}"></script>
<script src="{{ asset('static/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!-- Vendor JS -->
<script src="{{ asset('static/vendor/onscreen/dist/on-screen.umd.min.js') }}"></script>

<!-- Smooth scroll -->
<script src="{{ asset('static/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js') }}"></script>

<!-- Sweet Alerts 2 -->
<script src="{{ asset('static/vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

<!-- jQuery -->
<script src="{{ asset('static/vendor/jquery-3.6.0.min.js') }}"></script>

<!-- Simplebar -->
<script src="{{ asset('static/vendor/simplebar/dist/simplebar.min.js') }}"></script>

<!-- Charts -->
<script src="{{ asset('static/vendor/chartist/dist/chartist.min.js') }}"></script>
<script src="{{ asset('static/vendor/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>

<!-- Volt JS -->
<script src="{{ asset('static/js/volt.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('static/js/main.js') }}"></script>

{{-- all additional scripts (if any) will be loaded after the core js files --}}
@stack('scripts')

{{--
<!-- Slider -->
<script src="../../vendor/nouislider/distribute/nouislider.min.js"></script>

<!-- Datepicker -->
<script src="../../vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>


<!-- Moment JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>

<!-- Vanilla JS Datepicker -->
<script src="../../vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- Notyf -->
<script src="../../vendor/notyf/notyf.min.js"></script>


<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script> --}}
