@extends('layouts.admin', ['title' => 'Add Batch'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Add New Batch
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form class="form-floating" action="{{ route('batch.saveNew') }}" method="POST">
            {{ csrf_field() }}

            <div class="row g-2 mb-3">
                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <select class="form-select {{ $errors->has('type') ? 'is-invalid' : '' }}"
                            id="type" name="type" required>
                            <option value="b" selected>B.Tech</option>
                            <option value="m">M.Tech</option>
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
                            class="form-control {{ $errors->has('batch') ? 'is-invalid' : '' }}"
                            id="batch" placeholder="Batch Code" name="batch"
                            value="{{ old('batch') }}" required>
                        <label for="batch">Batch Code</label>
                        <small class="text-muted">Eg.: b23, m22 - Unique, concise & meaningful</small>

                        @if ($errors->has('batch'))
                            <div class="invalid-feedback">
                                {{ $errors->first('batch') }}
                            </div>
                        @endif

                    </div>
                </div>

                <div class="col-sm-4 mb-2">
                    <div class="form-floating">
                        <input type="number"
                            class="form-control {{ $errors->has('start_year') ? 'is-invalid' : '' }}"
                            id="start_year" placeholder="Start Year" name="start_year"
                            value="{{ old('start_year') }}" required>
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
                            value="{{ old('name') }}" required>
                        <label for="name">Name of Batch</label>

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
                    {{ route('batch.show') }}
                @endslot

                @slot('submitBtnTxt')
                    Add Batch
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection
