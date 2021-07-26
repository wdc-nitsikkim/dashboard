<!--

=========================================================
* Volt Pro - Premium Bootstrap 5 Dashboard
=========================================================

* Product Page: https://themesberg.com/product/admin-dashboard/volt-bootstrap-5-dashboard
* Copyright 2021 Themesberg (https://www.themesberg.com)
* License (https://themes.getbootstrap.com/licenses/)

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
    <link rel="apple-touch-icon" sizes="120x120" href="">
    <link rel="icon" type="image/png" sizes="32x32" href="">
    <link rel="icon" type="image/png" sizes="16x16" href="">

    @include('includes.styles')
</head>

<body>

    @yield('content')

    @include('includes.scripts')

</body>

</html>
