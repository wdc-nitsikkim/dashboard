@isset($help)
    <a href="{{ $help }}" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
        <span class="material-icons mx-1">help</span>
    </a>
@endisset

@isset($deptRedirect)
    @component('components.inline.anchorBtn', [
            'icon' => 'import_export',
            'href' => route('admin.department.select', ['redirect' => $deptRedirect]),
            'classes' => 'btn-outline-info',
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
            'classes' => 'btn-outline-info',
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
            'href' => route('admin.subject.select', ['redirect' => $subjectRedirect]),
            'classes' => 'btn-outline-info',
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
            'classes' => 'btn-outline-info'
        ])
        Trashed
    @endcomponent
@endisset


@isset($backRedirect)
    @component('components.inline.anchorBtn', [
            'icon' => 'keyboard_arrow_left',
            'href' => $backRedirect,
            'classes' => 'btn-outline-info'
        ])
        Back
    @endcomponent
@endisset
