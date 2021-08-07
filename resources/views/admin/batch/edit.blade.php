{{--
    $batch -> single batch model
--}}

@extends('layouts.admin', ['title' => 'Edit Batch'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Edit Batch
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

@php
    $type = old('type') ?? $batch['type'];
@endphp

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form class="form-floating" action="{{ route('admin.batch.update', $batch['id']) }}" method="POST">
            {{ csrf_field() }}

            <div class="row g-2 mb-3">
                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <select class="form-select {{ $errors->has('type') ? 'is-invalid' : '' }}"
                            id="type" name="type" required>
                            <option value="b" {{ $type == 'b' ? 'selected' : '' }}>B.Tech</option>
                            <option value="m" {{ $type == 'm' ? 'selected' : '' }}>M.Tech</option>
                        </select>
                        <label for="Type">Type</label>

                        @if ($errors->has('type'))
                            <div class="invalid-feedback">
                                {{ $errors->first('type') }}
                            </div>
                        @endif

                    </div>
                </div>

                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <input type="text"
                            class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}"
                            id="code" placeholder="Batch Code" name="code"
                            value="{{ old('code') ?? $batch['code'] }}" required>
                        <label for="code">Batch Code</label>
                        <small class="text-muted">Eg.: b23, m22 - Unique, concise & meaningful</small>

                        @if ($errors->has('code'))
                            <div class="invalid-feedback">
                                {{ $errors->first('code') }}
                            </div>
                        @endif

                    </div>
                </div>

                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <input type="number"
                            class="form-control {{ $errors->has('start_year') ? 'is-invalid' : '' }}"
                            id="start_year" placeholder="Start Year" name="start_year"
                            value="{{ old('start_year') ?? $batch['start_year'] }}" required>
                        <label for="start_year">Start Year</label>

                        @if ($errors->has('start_year'))
                            <div class="invalid-feedback">
                                {{ $errors->first('start_year') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-sm-6 mb-2">
                    <div class="form-floating">
                        <input type="text"
                            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            id="name" placeholder="Name of Batch" name="name"
                            value="{{ old('name') ?? $batch['name'] }}" required>
                        <label for="name">Name of Batch</label>

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            @component('components.form.timestamps', [
                    'createdAt' => $batch['created_at'],
                    'updatedAt' => $batch['updated_at']
                ])
            @endcomponent

            @component('components.form.footerEdit')
                @slot('returnRoute')
                    {{ route('admin.batch.show') }}
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection
