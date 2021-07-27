@extends('layouts.auth', ['title' => 'Login'])

@section('content')
    <main>

        <!-- Section -->
        <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
            <div class="container">
                <p class="text-center">
                    <a href="#!" class="d-flex align-items-center justify-content-center">
                        <span class="material-icons">keyboard_arrow_left</span>
                        Back to homepage
                    </a>
                </p>

                <div class="row justify-content-center">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-0 h3">Sign in</h1>
                            </div>
                            <form action="" class="mt-4" method="POST">
                                {{ csrf_field() }}

                                <div class="form-group mb-4">
                                    <label for="email">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <span class="material-icons">email</span>
                                        </span>
                                        <input type="email" class="form-control" placeholder="example@nitsikkim.ac.in"
                                            name="email" id="email" autofocus required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group mb-4">
                                        <label for="password">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <span class="material-icons">lock</span>
                                            </span>
                                            <input type="password" placeholder="Password" class="form-control"
                                                name="password" id="password" required>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-top mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="remember" id="remember">
                                            <label class="form-check-label mb-0" for="remember">
                                                Remember me
                                            </label>
                                        </div>
                                        {{-- <div><a href="#!" class="small text-right">Lost
                                                password?</a></div> --}}
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-gray-800">Authenticate</button>
                                </div>
                            </form>
                            <div class="mt-3 mb-4 text-center">
                                <span class="fw-normal">or login with</span>
                            </div>
                            <div class="d-flex justify-content-center my-4">
                                <a href="#" class="btn btn-outline-gray-500 me-2"
                                    aria-label="facebook button" title="facebook button">
                                    <img class="icon icon-xxs" src="{{ asset('static/images/google.png') }}" aria-hidden="true" data-prefix="fab"
                                        data-icon="facebook-f" role="img">
                                        <span class="mx-2">Google</span>
                                </a>
                            </div>
                            {{-- <div class="d-flex justify-content-center align-items-center mt-4">
                                <span class="fw-normal">
                                    Not registered?
                                    <a href="./sign-up.html" class="fw-bold">Create account</a>
                                </span>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
