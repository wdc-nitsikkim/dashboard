{{--
    $department -> single department model
    $subjectTypes -> collection of subjectType model
--}}

@extends('layouts.admin', ['title' => 'Add Subjects'])

@section('content')

@component('components.page.heading')
    @slot('heading')
        Add Subjects
    @endslot

    @slot('subheading')
        <h5>Department of {{ $department->name }}</h5>
        <h6>Add subjects offered/belonging by this department</h6>
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!'
        ])
    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <form class="form-floating"
            action="{{ route('admin.subjects.saveNew', $department) }}"
            method="POST" id="bulk-subjects-form">
            {{ csrf_field() }}

            @component('components.table.main', [
                'attr' => 'id = "subjects-table"'
            ])
                @slot('head')
                    @component('components.table.head', [
                        'items' => [ 'Type', 'Code', 'Name', '-' ]
                    ])
                    @endcomponent
                @endslot

                @slot('body')
                        <tr row-clone>
                            <td class="col-2">
                                <select class="form-control form-select" name="subject_type_id[]" required>
                                    @foreach ($subjectTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ $loop->first ? 'selected' : '' }}>
                                            {{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="col-3">
                                <input type="text" class="form-control" placeholder="2-Digit Code"
                                    name="code[]" minlength="2" maxlength="2" required>
                                <small class="helper-text small">Only last 2 charaters of the subject are required</small>
                            </td>
                            <td class="col-6">
                                <input type="text" class="form-control" placeholder="Name"
                                    name="name[]" required>
                            </td>
                            <td class="col-1">
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
                    {{ route('root.home') }}
                @endslot

                @slot('submitBtnTxt')
                    Add Subjects
                @endslot
            @endcomponent

        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('static/js/json-formerror-handler.js') }}"></script>
    <script src="{{ asset('static/js/subjects.js') }}"></script>
@endpush
