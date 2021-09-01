@component('components.page.heading')
    @slot('heading')
        <span class=" {{ $student->deleted_at != null ? 'text-danger' : '' }}">
            <span class="fw-bolder">
                {{ $student->name }}</span> ({{ $student->roll_number }})
        </span>
    @endslot

    @slot('subheading')
        <h5>
            {{ 'Department of ' . $student->department->name }}
            <br>
            @include('admin.students.partials.subheading', [
                'batch' => $student->batch
            ])
            <br>
            @if ($student->deleted_at != null)
                <span class="text-danger fw-bolder">This student has been deleted!</span>
            @endif
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
