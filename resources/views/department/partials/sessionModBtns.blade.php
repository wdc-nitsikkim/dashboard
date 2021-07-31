@if (session()->has(CustomHelper::getSessionConstants()['selectedDepartment']))
    @component('components.anchorBtn', [
            'icon' => 'clear',
            'href' => route('root.clearSession'),
            'classes' => 'btn-outline-danger',
            'tooltip' => true
        ])
        @slot('attr')
            data-bs-placement="left" title="Clear Session" spoof spoof-method="POST"
        @endslot
    @endcomponent
@endif

@component('components.anchorBtn', [
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
