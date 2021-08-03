@extends('layouts.admin', ['title' => 'Edit Department'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Edit Existing Department
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form class="form-floating" action="{{ route('admin.department.update', $department['id']) }}" method="POST">
            {{ csrf_field() }}

            <div class="row g-2 mb-3">
                <div class="col-md-4 col-sm-12 mb-2">
                    <div class="form-floating">
                        <input type="text"
                            class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}"
                            id="code" placeholder="Code" name="code"
                            value="{{ old('code') ?? $department['code'] }}" required>
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
                            value="{{ old('name') ?? $department['name'] }}" required>
                        <label for="name">Name of Department</label>

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            @component('components.form.timestamps', [
                    'createdAt' => $department['created_at'],
                    'updatedAt' => $department['updated_at']
                ])
            @endcomponent

            @component('components.form.footerEdit')
                @slot('returnRoute')
                    {{ route('admin.department.show') }}
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection
