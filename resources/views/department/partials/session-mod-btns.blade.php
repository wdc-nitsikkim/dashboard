@if (session()->has(CustomHelper::getSessionConstants()['selectedDepartment']))
    <a href="{{ route('root.clearSession') }}"
        class="btn btn-outline-danger d-inline-flex align-items-center mx-1"
        data-bs-toggle="tooltip" data-bs-placement="left" title="Clear Session" spoof spoof-method="POST">
        <span class="material-icons mx-1">clear</span>
    </a>
@endif

<a href="{{ route('department.select') }}" class="btn btn-outline-info d-inline-flex align-items-center"
    data-bs-toggle="tooltip" data-bs-placement="left" title="Change Department">
    <span class="material-icons mx-1">import_export</span>
    Switch
</a>
