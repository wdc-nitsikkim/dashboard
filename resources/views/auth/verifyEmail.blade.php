@extends('layouts.auth', ['title' => 'Verify Email'])

@section('content')
    <main>

        <!-- Section -->
        <section class="mt-3 mt-lg-4 mb-3 bg-soft d-flex align-items-center">
            <div class="container">
                <p class="text-center">
                    <a href="{{ route('root.home') }}" class="d-flex align-items-center justify-content-center">
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
                                <h1 class="mb-0 h3">Verify Email</h1>
                                <p class="small">An email will be sent to the registered email
                                    address containing a link to verify</p>
                                <p class="text-info small">You need to be logged-in on the device
                                    in which you will be clicking the link.</p>
                            </div>

                            <form action="{{ route('users.verifyEmail.sendMail') }}" class="mt-4" method="POST">
                                {{ csrf_field() }}

                                <div class="form-group mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <span class="material-icons">email</span>
                                        </span>
                                        <input type="email" placeholder="Email"
                                            class="form-control" name="email"
                                            value="{{ Auth::user()->email }}" readonly required>
                                    </div>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-gray-800">Send Link</button>
                                </div>

                                <p class="mb-3 text-center">
                                    <a class="small d-inline mx-1" href="{{ route('users.account', Auth::id()) }}">My Account</a>
                                    <a class="small d-inline mx-1" href="{{ route('logout') }}">Logout</a>
                                </p>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
