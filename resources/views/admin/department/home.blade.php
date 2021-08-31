{{--
    $department -> single department model
    $advancedAccess -> boolean
--}}

@extends('layouts.admin', ['title' => 'Home - ' . $department['name']])

@section('content')

@component('components.page.heading')
    @slot('heading')
        {{ $department['name'] ? 'Department of ' . $department['name'] : 'Department Homepage' }}
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!',
            'deptRedirect' => ''
        ])
    @endslot
@endcomponent

@php
    $deptParam = [ 'department_id' => $department->id ];
@endphp

<div class="row row-cols-1 row-cols-md-3 g-2 mb-4">
    <div class="col">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <span class="material-icons me-1">school</span>
                    Students
                </h5>
                <p class="card-text">Update student details</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.students.search', $deptParam) }}"
                    class="small text-info">Go to page</a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <span class="material-icons me-1">people</span>
                    Faculty & Staff
                </h5>
                <p class="card-text">Update faculty or staff members</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.profiles.search', $deptParam) }}"
                    class="small text-info">Go to page</a>
                <a href="{{ route('admin.department.orderPeople', $department->code) }}"
                    data-bs-toggle="tooltip" title="Allows you to reorder the sequence of faculties/staff in department"
                    class="small text-info ms-2">Re-order</a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <span class="material-icons me-1">supervised_user_circle</span>
                    Researchers
                </h5>
                <p class="card-text">Update researchers</p>
            </div>
            <div class="card-footer">
                <a href="#!" class="small text-info">Go to page</a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <span class="material-icons me-1">library_books</span>
                    Add Base Subjects
                </h5>
                <p class="card-text">Add base subjects offered by this department</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.subjects.add', $department) }}" class="small text-info">Add</a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <span class="material-icons me-1">class</span>
                    Register Subjects
                </h5>
                <p class="card-text">Register subjects taught to students of this department (batchwise)</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.subjects.addReg', $department) }}" class="small text-info">Follow Link</a>
            </div>
        </div>
    </div>
</div>

@if ($advancedAccess)

    <div class="mb-3">
        <h3 class="text-tertiary">
            <span class="material-icons">
                admin_panel_settings
            </span>
            Advanced Access
        </h3>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-2 mb-4">
        <div class="col">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <span class="material-icons me-1">auto_awesome</span>
                        Manage Pages
                    </h5>
                    <p class="card-text">Update content of pages, announcements, etc</p>
                </div>
                <div class="card-footer">
                    <a href="#!" class="small text-info">Follow link</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <span class="material-icons me-1">lock</span>
                        Card Title
                    </h5>
                    <p class="card-text">Description</p>
                </div>
                <div class="card-footer">
                    <a href="#!" class="small text-info">Follow Link</a>
                </div>
            </div>
        </div>
    </div>

@endif

@endsection
