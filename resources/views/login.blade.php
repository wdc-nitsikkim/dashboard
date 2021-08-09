@extends('layouts.auth', ['title' => 'Login'])

@section('content')
    <main>

        <!-- Section -->
        <section class="mt-3 mt-lg-4 mb-3 bg-soft d-flex align-items-center">
            <div class="container">
                <p class="text-center">
                    <a href="/" class="d-flex align-items-center justify-content-center">
                        <span class="material-icons">keyboard_arrow_left</span>
                        Go Back
                    </a>
                </p>

                <div class="row justify-content-center">

                    <div class="col-12 mb-3 d-flex align-items-center justify-content-center">
                        <div class="bg-white shadow border-0 rounded border-light p-4
                            p-lg-5 w-100 fmxw-500">

                            @if (session()->has('status'))
                                <div class="text-center text-md-center mb-3 mt-md-0">
                                    <div class="alert alert-danger">
                                        {{ session('message') }}
                                    </div>
                                </div>
                            @endif

                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-0 h3">Sign in</h1>
                            </div>
                            <form action="{{ route('auth.signin.default') }}" class="mt-4" method="POST">
                                {{ csrf_field() }}

                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <span class="material-icons">email</span>
                                        </span>
                                        <input type="email" class="form-control"
                                            placeholder="example@nitsikkim.ac.in"
                                            name="email" id="email" value="{{ old('email') }}"
                                            autofocus required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group mb-4">
                                        <label for="password">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <span class="material-icons">lock</span>
                                            </span>
                                            <input type="password" placeholder="Password"
                                                class="form-control"
                                                name="password" id="password" required>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-top mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="true"
                                                name="remember" id="remember">
                                            <label class="form-check-label mb-0" for="remember">
                                                Remember me
                                            </label>
                                        </div>
                                        <div><a href="#!" class="small text-right">Lost
                                                password?</a></div>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-gray-800">Authenticate</button>
                                </div>
                            </form>
                            <div class="mt-3 mb-4 text-center">
                                <span class="fw-normal">or</span>
                            </div>

                            <div id="g_id_onload"
                                data-client_id="{{ config('app.g_signin_client_id') }}"
                                data-context="signin"
                                data-ux_mode="redirect"
                                data-login_uri="{{ config('app.g_signin_redirect_uri') }}"
                                data-nonce=""
                                data-close_on_tap_outside="false">
                            </div>

                            <div class="d-flex justify-content-center my-3">
                                <div class="g_id_signin"
                                    data-type="standard"
                                    data-shape="pill"
                                    data-theme="filled_black"
                                    data-text="signin_with"
                                    data-size="large"
                                    data-logo_alignment="left">
                                </div>
                            </div>

                            <div class="d-flex justify-content-center align-items-center mt-4">
                                <span class="fw-normal">
                                    Not registered?
                                    <a href="{{ route('register') }}" class="fw-bolder">
                                        Create account</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('static/vendor/google/gsi-client.min.js') }}" async defer></script>
@endpush
