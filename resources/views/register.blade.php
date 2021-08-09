{{--
    $roles -> if $select is set, array
    $role -> string
--}}

@extends('layouts.auth', ['title' => 'Register'])

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

                            @isset($select)
                                <div class="text-center text-md-center mb-4 mt-md-0">
                                    <h1 class="mb-0 h3">Select account type</h1>
                                    <p class="small">Your account will be verified before being activated,
                                        choose appropriate type which applies to you
                                        <br>
                                        <span class="fw-bold">Registrations are currently open for below
                                            roles</span>
                                    </p>
                                </div>

                                <div class="row justify-content-center mb-3">

                                    @foreach ($roles as $role)
                                        @component('components.inline.anchorBtn', [
                                                'href' => route('register', $role),
                                                'classes' => 'btn btn-outline-tertiary mx-1 mb-2'
                                            ])
                                            @slot('attr')
                                                style="width: fit-content"
                                            @endslot
                                            {{ ucfirst($role) }}
                                        @endcomponent
                                    @endforeach

                                </div>
                            @else
                                <div class="text-center text-md-center mb-4 mt-md-0">
                                    <h1 class="mb-0 h3">Create Account</h1>
                                    <p class="small">Account type: <span class="fw-bold">
                                        {{ strtoupper($role) }}</span>
                                        <a href="{{ route('register') }}" data-bs-toggle="tooltip"
                                            title="Change type" data-bs-placement="right">
                                            <span class="small fw-bolder material-icons">import_export</span></a>
                                    </p>
                                </div>
                                <form action="{{ route('auth.signup.default') }}" class="mt-4" method="POST">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="role" value="{{ $role }}">

                                    <div class="form-group mb-3">
                                        <label for="name">Your Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <span class="material-icons">person</span>
                                            </span>
                                            <input type="text" class="form-control
                                                {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                                placeholder="Name"
                                                name="name" id="name" autofocus required
                                                value="{{ Session::get('name', null) ?? old('name') }}">

                                            @if ($errors->has('name'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('name') }}
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="email">Your Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <span class="material-icons">email</span>
                                            </span>
                                            <input type="email" class="form-control
                                                {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                placeholder="example@nitsikkim.ac.in"
                                                name="email" id="email" required
                                                value="{{ Session::get('email', null) ?? old('email') }}">

                                            @if ($errors->has('email'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('email') }}
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="mobile">Your Mobile</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <span class="material-icons">smartphone</span>
                                            </span>
                                            <input type="number" class="form-control
                                                {{ $errors->has('mobile') ? 'is-invalid' : '' }}"
                                                placeholder="1234567890"
                                                name="mobile" id="mobile" required
                                                value="{{ old('mobile') }}">

                                            @if ($errors->has('mobile'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('mobile') }}
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-group mb-3">
                                            <label for="password">Your Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <span class="material-icons">lock</span>
                                                </span>
                                                <input type="password" placeholder="Password"
                                                    class="form-control
                                                    {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                                    id="password" name="password" required>

                                                @if ($errors->has('password'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('password') }}
                                                    </div>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="confirm_password">Confirm Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <span class="material-icons">password</span>
                                                </span>
                                                <input type="password" placeholder="Confirm Password"
                                                    class="form-control"  id="password_confirmation"
                                                    name="password_confirmation" required>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-gray-800">Sign up</button>
                                    </div>
                                </form>
                            @endisset

                            <div class="d-flex justify-content-center align-items-center mt-4">
                                <span class="fw-normal">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="fw-bolder">Login here</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
