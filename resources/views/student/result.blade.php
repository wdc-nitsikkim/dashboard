{{--
    $student -> single student model (nested relations)
    $semesters -> collection of semester model
    $currentSemester -> single semester model
    $subjects -> collection of DepartmentSubjectsTaught model (nested relations)
    $resultTypes -> collection of resultType model
--}}

@extends('layouts.admin', ['title' => 'Student Result - ' . $student->name])

@section('content')

@component('student.partials.pageHeading', [
        'student' => $student
    ])

    <h5 class="fw-bolder">{{ $currentSemester->name }}</h5>

    @slot('sideBtns')

        <div>

            @include('partials.pageSideBtns', [
                'help' => '#!',
                'print' => '.table-responsive',
                'backRedirect' => route('student.home', $student->roll_number)
            ])

            <button class="btn btn-outline-info d-inline-flex align-items-center dropdown-toggle ms-1
                mb-2" data-bs-toggle="dropdown">
                Semester
                <span class="material-icons ms-1">keyboard_arrow_down</span>
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2">

                @foreach ($semesters as $sem)
                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ route('student.result', [
                            'student_by_roll_number' => $student->roll_number,
                            'semester' => $sem->id
                        ]) }}">
                        {{ $sem->name }}</a>
                @endforeach

            </div>
        </div>

    @endslot
@endcomponent

<div class="card border-0 shadow mb-4">
    <div class="card-body">

        @if ($subjects->count() == 0)
            <h5 class="mb-0 text-center text-danger">No results found!</h5>
            <h6 class="text-center">No subjects found for the selected semester</h6>
            <p class="text-center">
                @component('components.inline.anchorBack', [
                    'href' => route('student.home', $student->roll_number)
                ])
                @endcomponent
            </p>
        @else
            @component('components.table.main', [
                'attr' => 'id="students"',
                'classes' => 'table-striped table-hover'
            ])
                @slot('head')
                    <thead class="thead-light">
                        <th class="border-0 col rounded-start">Code</th>
                        <th class="border-0 col">Subject</th>

                        @foreach ($resultTypes as $type)
                            <th class="border-0 col text-center
                                {{ $loop->last ? 'rounded-end' : '' }}">
                                <span class="text-wrap">{{ $type->name }}</span>
                                <br>
                                <span class="mt-1 text-info">({{ $type->max_marks }})</span>
                            </th>
                        @endforeach

                    </thead>
                @endslot

                @slot('body')
                    @foreach ($subjects as $subject)

                        <tr>
                            <td class="fw-bolder text-info">
                                {{ strtoupper($subject->subject_code) }}
                            </td>
                            <td class="fw-bolder">
                                {{ $subject->subject->name }}
                            </td>

                            @foreach ($resultTypes as $type)
                                @php
                                    $result = $student->result
                                        ->where('registered_subject_id', $subject->id)
                                        ->where('result_type_id', $type->id)->first();

                                    $scoreClass = 'text-danger';
                                    $score = '';

                                    if ($result != null && $result->deleted_at == null) {
                                        $score = $result->score;
                                        $percentage = $result->percentage;
                                        if ($percentage >= 81) {
                                            $scoreClass = 'text-success';
                                        } else if ($percentage >= 51) {
                                            $scoreClass = 'text-info';
                                        } else if ($percentage >= 33) {
                                            $scoreClass = 'text-primary';
                                        }
                                    }
                                @endphp

                                <td class="text-center"
                                    @if ($result != null)
                                        data-bs-toggle="tooltip"
                                        title="{{ ($result->percentage ?? 0) . ' %' }},
                                        Updated: {{ $result->updated_at ?? '-' }}"
                                    @endif
                                    >
                                    <span class="{{ $scoreClass }} fw-bolder">
                                        {{ strlen($score) == 0 ? '-' : $score }}
                                    </span>
                                </td>

                            @endforeach

                        </tr>

                    @endforeach
                @endslot
            @endcomponent

        @endif

    </div>
</div>
@endsection
