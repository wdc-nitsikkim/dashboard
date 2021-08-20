@extends('layouts.auth', ['title' => 'Verify Identity'])

@section('content')
    <main>

        <section class="mt-3 mt-lg-4 mb-3 bg-soft d-flex align-items-center">
            <div class="container">
                <p class="text-center">
                    <a href="{{ request()->previous ?? route('root.default') }}" class="d-flex align-items-center
                        justify-content-center">
                        <span class="material-icons">keyboard_arrow_left</span>
                        Go Back
                    </a>
                </p>

                <div class="row justify-content-center form-bg-image">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="bg-white shadow border-0 rounded p-4 p-lg-5 w-100 fmxw-500">
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <div class="avatar avatar-lg mx-auto mb-3">

                                    @if (isset(Auth::user()->image) && Storage::disk('public')->exists(Auth::user()->image))
                                        <img class="rounded-circle" alt="user-img"
                                            src="{{ asset(Storage::url(Auth::user()->image)) }}"/>
                                    @elseif (isset(Auth::user()->image) && filter_var(Auth::user()->image, FILTER_VALIDATE_URL))
                                        <img class="rounded-circle" alt="url-image"
                                            src="{{ Auth::user()->image }}"/>
                                    @else
                                        <span class="material-icons icon-xxx-large">person_outline</span>
                                    @endif

                                </div>
                                <h1 class="h3">{{ Auth::user()->name }}</h1>
                                <p class="text-gray">
                                    Re-enter your password to cotinue<br>
                                    <span class="text-info small">We won't ask for your password
                                        again for some time</span>
                                </p>
                            </div>

                            <form class="mt-5" action="{{ route('root.confirmPassword') }}" method="POST">
                                {{ csrf_field() }}

                                <input type="hidden" name="intended"
                                    value="{{ request()->intended ?? route('root.default') }}">

                                <div class="form-group mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <span class="material-icons">lock</span>
                                        </span>
                                        <input type="password" placeholder="Password"
                                            class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                            name="password" id="password" autofocus required>

                                        @if ($errors->has('password'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password') }}
                                            </div>
                                        @endif

                                    </div>
                                </div>

                                <div class="d-grid mt-3">
                                    <button type="submit" class="btn btn-gray-800">Unlock</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
