{{--
    $department -> single department model
    $batch -> single batch model
--}}

@extends('layouts.admin', ['title' => 'Students - Bulk Insert'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Add Students
    @endslot

    @slot('subheading')
        @include('admin.students.partials.subheading', ['batch' => $batch])
        -
        {{ $department['name'] }}
    @endslot

    @slot('sideButtons')
        <a href="#!" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
            <span class="material-icons mx-1">help</span>
        </a>
    @endslot
@endcomponent

@php
    $baseRouteParams = [
        'dept' => $department,
        'batch' => $batch
    ];
@endphp

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form class="form-floating"
            action="{{ route('admin.students.bulkInsert', $baseRouteParams) }}"
            method="POST" id="bulk-students-form">
            {{ csrf_field() }}

            @component('components.table.main', [
                'attr' => 'id = "students-table"'
            ])
                @slot('head')
                    @component('components.table.head', [
                        'items' => ['Name', 'Roll Number', 'Email', '-']
                    ])
                    @endcomponent
                @endslot

                @slot('body')
                        <tr row-clone>
                            <td>
                                <input type="text" class="form-control" placeholder="Name"
                                    name="name[]" required>
                            </td>
                            <td>
                                <input type="text" class="form-control" placeholder="Roll Number"
                                    name="roll_number[]"
                                    required>
                            </td>
                            <td>
                                <input type="email" class="form-control" placeholder="Email"
                                    name="email[]" required>
                            </td>
                            <td>
                                <span class="material-icons cur-pointer text-danger" delete-row>delete</span>
                            </td>
                        </tr>

                @endslot
            @endcomponent

            <div class="row mt-2 mb-3">
                <div class="col text-end">
                    <button class="btn btn-outline-primary" type="button"
                        id="add-row">
                        Add Row
                        <span class="material-icons ms-1">add</span>
                    </button>
                </div>
            </div>

            @component('components.form.footerAdd')
                @slot('returnRoute')
                    {{ route('admin.students.show', $baseRouteParams) }}
                @endslot

                @slot('submitBtnTxt')
                    Insert Students
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('static/js/json-formerror-handler.js') }}"></script>
    <script src="{{ asset('static/js/students.js') }}"></script>
@endpush
