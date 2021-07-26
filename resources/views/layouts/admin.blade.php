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

    @include('includes.meta-head')

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
