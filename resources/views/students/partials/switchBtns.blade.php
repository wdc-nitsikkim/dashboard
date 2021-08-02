<a href="#!" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
    <span class="material-icons mx-1">help</span>
</a>

@component('components.inline.anchorBtn', [
        'icon' => 'import_export',
        'href' => route('department.select'),
        'classes' => 'btn-outline-info',
        'tooltip' => true
    ])
    @slot('attr')
        data-bs-placement="left" title="Change Department"
    @endslot
    Department
@endcomponent

@component('components.inline.anchorBtn', [
        'icon' => 'import_export',
        'href' => route('students.selectBatch', $department),
        'classes' => 'btn-outline-info',
        'tooltip' => true
    ])
    @slot('attr')
        data-bs-placement="left" title="Change Batch"
    @endslot
    Batch
@endcomponent
