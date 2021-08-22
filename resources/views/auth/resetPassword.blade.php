@extends('layouts.auth', ['title' => 'Reset Password'])

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
                                    <div class="alert
                                        alert-{{ session('status') == 'fail' ? 'danger' : session('status') }}">
                                        {{ session('message') }}
                                    </div>
                                </div>
                            @endif

                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-0 h3">Reset Password</h1>
                                <p class="small">{{ $userInfo ?? '' }}</p>
                            </div>

                            <form class="mt-4" method="POST">
                                {{ csrf_field() }}

                                <div class="form-group mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <span class="material-icons">lock</span>
                                        </span>
                                        <input type="password" placeholder="New Password"
                                            class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                            name="password" autofocus required>

                                        @if ($errors->has('password'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <span class="material-icons">https</span>
                                        </span>
                                        <input type="password" placeholder="Confirm Password"
                                            class="form-control"
                                            name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-gray-800">Proceed</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
