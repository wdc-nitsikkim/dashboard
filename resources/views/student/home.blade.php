{{--
    $student -> single student model (nested relations)
--}}

@extends('layouts.admin', ['title' => 'Student Profile - ' . $student->roll_number])

@section('content')

@component('components.page.heading')
    @slot('heading')
        {{ $student->name }} ({{ $student->roll_number }})
    @endslot

    @slot('subheading')
        <h5>
            {{ 'Department of ' . $student->department->name }}
            <br>
            @include('admin.students.partials.subheading', [
                'batch' => $student->batch
            ])
        </h5>
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!',
        ])
    @endslot
@endcomponent

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
                <a href="#!"
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
                <a href="#!"
                    class="small text-info">Go to page</a>
                <a href="#!"
                    class="small text-info ms-2">Manage</a>
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
                    <span class="material-icons me-1">today</span>
                    Timetable & Syllabus
                </h5>
                <p class="card-text">Update timetable/syllabus semester-wise</p>
            </div>
            <div class="card-footer">
                <a href="#!" class="small text-info">Go to page</a>
            </div>
        </div>
    </div>
</div>

@endsection
