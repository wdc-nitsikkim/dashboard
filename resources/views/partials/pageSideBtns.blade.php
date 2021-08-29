@isset($help)
    <a href="{{ $help }}" class="btn btn-light d-inline-flex align-items-center mb-2">
        <span class="material-icons">help</span>
    </a>
@endisset

@isset($searchRedirect)
    @component('components.inline.anchorBtn', [
        'icon' => 'search',
        'href' => $searchRedirect,
        'classes' => 'btn-outline-primary mb-2'
    ])
        Search
    @endcomponent
@endisset

@isset($deptRedirect)
    @component('components.inline.anchorBtn', [
        'icon' => 'import_export',
        'href' => route('admin.department.select', ['redirect' => $deptRedirect]),
        'classes' => 'btn-outline-info mb-2',
        'tooltip' => true
    ])
        @slot('attr')
            data-bs-placement="left" title="Change Department"
        @endslot
        Department
    @endcomponent
@endisset

@isset($batchRedirect)
    @component('components.inline.anchorBtn', [
        'icon' => 'import_export',
        'href' => route('admin.batch.select', ['redirect' => $batchRedirect]),
        'classes' => 'btn-outline-info mb-2',
        'tooltip' => true
    ])
        @slot('attr')
            data-bs-placement="left" title="Change Batch"
        @endslot
        Batch
    @endcomponent
@endisset

@isset($subjectRedirect)
    @component('components.inline.anchorBtn', [
        'icon' => 'import_export',
        'href' => route('admin.subjects.select', ['redirect' => $subjectRedirect]),
        'classes' => 'btn-outline-info mb-2',
        'tooltip' => true
    ])
        @slot('attr')
            data-bs-placement="left" title="Change Subject"
        @endslot
        Subject
    @endcomponent
@endisset

@isset($trashRedirect)
    @component('components.inline.anchorBtn', [
        'icon' => 'restore_from_trash',
        'href' => $trashRedirect,
        'classes' => 'btn-outline-info mb-2'
    ])
        Trashed
    @endcomponent
@endisset


@isset($backRedirect)
    @component('components.inline.anchorBtn', [
        'icon' => 'keyboard_arrow_left',
        'href' => $backRedirect,
        'classes' => 'btn-outline-info mb-2'
    ])
        Back
    @endcomponent
@endisset
