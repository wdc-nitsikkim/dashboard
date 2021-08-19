<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    @include('includes.meta-head')

    <title>Oops, An error occurred!</title>

    <!-- Styles -->
    <style>
        @font-face {
            font-family: 'Raleway';
            font-style: normal;
            font-weight: 100;
            src: url({{ asset('static/fonts/Raleway-Light.ttf') }}) format('truetype');
        }

        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 3rem;
            padding: 20px;
        }

        .bolder {
            font-weight: 700;
        }

        .larger {
            font-size: 4rem;
        }

        .links > a {
            color: #1565C0;
            font-size: 1.3em;
            text-decoration: none;
            padding: 0 4px;
        }
    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title">
                <div class="bolder larger">
                    {{ $statusCode }} | {{ $shortText }}
                </div>
               {{ $message }}
            </div>

            <div class="links">
                <a href="{{ url()->previous() }}">Go Back</a>
                <a href="{{ config('app.url') }}">Home</a>
                <a href="https://nitsikkim.ac.in">NIT Sikim</a>
            </div>
        </div>
    </div>
</body>

</html>
