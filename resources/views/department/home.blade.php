@extends('layouts.admin', ['title' => 'Department'])

@section('content')
<div class="my-3">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h3">
                {{ $department['name'] ? 'Department of ' . $department['name'] : 'Department Homepage' }}
            </h1>
            {{-- <p class="mb-0"></p> --}}
        </div>
        <div>
            @include('department.partials.session-mod-btns')
        </div>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-2 mb-4">
    <div class="col">
        <div class="card shadow h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <span class="material-icons mx-1">school</span>
                    Students
                </h5>
                <p class="card-text">Update student details</p>
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
                    <span class="material-icons mx-1">people</span>
                    Faculty & Staff
                </h5>
                <p class="card-text">Update faculty or staff members</p>
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
                    <span class="material-icons mx-1">supervised_user_circle</span>
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
                    <span class="material-icons mx-1">today</span>
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
