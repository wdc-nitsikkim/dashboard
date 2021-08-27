@component('components.page.heading')
    @slot('heading')
        <span class="fw-bolder">{{ $student->name }}</span> ({{ $student->roll_number }})
    @endslot

    @slot('subheading')
        <h5>
            {{ 'Department of ' . $student->department->name }}
            <br>
            @include('admin.students.partials.subheading', [
                'batch' => $student->batch
            ])
        </h5>

        {{ $slot ?? '' }}
    @endslot

    @slot('sideButtons')
        @include('partials.pageSideBtns', [
            'help' => '#!',
        ])
    @endslot
@endcomponent
