@extends('layouts.admin', ['title' => 'Add Department'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Add New Department
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form class="form-floating" action="{{ route('admin.department.saveNew') }}" method="POST">
            {{ csrf_field() }}

            <div class="row g-2 mb-3">
                <div class="col-md-4 col-sm-12 mb-2">
                    <div class="form-floating">
                        <input type="text"
                            class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}"
                            id="code" placeholder="Code" name="code"
                            value="{{ old('code') }}" required>
                        <label for="code">Code</label>
                        <small class="text-muted">2 - 4 characters, Unique</small>

                        @if ($errors->has('code'))
                            <div class="invalid-feedback">
                                {{ $errors->first('code') }}
                            </div>
                        @endif

                    </div>
                </div>

                <div class="col-md-8 col-sm-12 mb-2">
                    <div class="form-floating">
                        <input type="text"
                            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            id="name" placeholder="Name of Department" name="name"
                            value="{{ old('name') }}" required>
                        <label for="name">Name of Department</label>

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            @component('components.form.footerAdd')
                @slot('returnRoute')
                    {{ route('admin.department.show') }}
                @endslot

                @slot('submitBtnTxt')
                    Add Department
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection
