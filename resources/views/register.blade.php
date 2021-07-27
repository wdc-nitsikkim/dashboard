@extends('layouts.auth', ['title' => 'Register'])

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
                                <h1 class="mb-0 h3">Create Account </h1>
                            </div>
                            <form action="" class="mt-4" method="POST">
                                {{ csrf_field() }}

                                <div class="form-group mb-4">
                                    <label for="email">Your Email</label>
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
                                        <label for="password">Your Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <span class="material-icons">lock</span>
                                            </span>
                                            <input type="password" placeholder="Password" class="form-control" id="password"
                                                name="password" required>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="confirm_password">Confirm Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <span class="material-icons">password</span>
                                            </span>
                                            <input type="password" placeholder="Confirm Password" class="form-control"
                                                name="" id="confirm_password" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="remember">
                                            <label class="form-check-label fw-normal mb-0" for="remember">
                                                I agree to the <a href="#" class="fw-bold">terms and conditions</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-gray-800">Sign up</button>
                                </div>
                            </form>

                            <div class="d-flex justify-content-center align-items-center mt-4">
                                <span class="fw-normal">
                                    Already have an account?
                                    <a href="#!" class="fw-bold">Login here</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
