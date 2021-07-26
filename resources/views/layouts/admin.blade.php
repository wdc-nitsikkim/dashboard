<!--

=========================================================
* Volt Free - Bootstrap 5 Dashboard
=========================================================

* Product Page: https://themesberg.com/product/admin-dashboard/volt-bootstrap-5-dashboard
* Copyright 2021 Themesberg (https://www.themesberg.com)
* License (https://themesberg.com/licensing)

* Designed and coded by https://themesberg.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. Please contact us to request a removal.

-->

<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $title ?? 'Admin Dashboard' }}</title>

    <!-- Primary Meta Tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="title" content="Admin Dashboard" />
    <meta name="author" content="WDC, NIT Sikkim" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <link rel="canonical" href="" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" href="{{ asset('static/images/icons/apple-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('static/images/icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('static/images/icons/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('static/images/icon.webp') }}">

    @include('includes.styles')
</head>

<body>

    @include('includes.sidenav')

    <main class="content">

        @include('includes.nav')

        @yield('content')

        @include('includes.footer')

    </main>

    @include('includes.scripts')

</body>

</html>
