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

    @isset($sideBtns)
        @slot('sideButtons')
            @if (is_array($sideBtns))
                @include('partials.pageSideBtns', $sideBtns)
            @else
                {!! $sideBtns !!}
            @endif
        @endslot
    @endisset
@endcomponent
