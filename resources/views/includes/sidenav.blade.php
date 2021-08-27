<nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
    {{-- visible in mobile view --}}
    <a class="navbar-brand me-lg-5" href="/">
        <img class="navbar-brand-dark" src="{{ asset('static/images/logo.svg') }}" alt="Logo" />
        <img class="navbar-brand-light" src="{{ asset('static/images/logo.svg') }}" alt="Logo" />
    </a>
    <div class="d-flex align-items-center">
        <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

@php
    $sessionConsts = CustomHelper::getSessionConstants();

    $isDeptSelected = session()->has($sessionConsts['selectedDepartment']);
    $isBatchSelected = session()->has($sessionConsts['selectedBatch']);
    $isSubjectSelected = session()->has($sessionConsts['selectedSubject']);

    $department = session($sessionConsts['selectedDepartment']);
    $batch = session($sessionConsts['selectedBatch']);
    $subject = session($sessionConsts['selectedSubject']);
@endphp

<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
    {{-- visible in mobile view --}}
    <div class="sidebar-inner px-4 pt-3">
        <div
            class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
            <div class="d-flex align-items-center">
                <div class="avatar-lg me-4">

                    @component('components.image', [
                        'image' => Auth::user()->image,
                        'classes' => 'card-img-top border-white'
                    ])
                    @endcomponent

                </div>
                <div class="d-block">
                    <h2 class="h5 mb-3">
                        {{ Auth::user()->name ?? '-' }}</h2>
                    <a href="{{ route('logout') }}"
                        class="btn btn-secondary btn-sm d-inline-flex align-items-center">
                        <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Sign Out
                    </a>
                </div>
            </div>
            <div class="collapse-close d-md-none">
                <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>

        <ul class="nav flex-column pt-3 pt-md-0">
            <li class="nav-item">
                <a href="/" class="nav-link d-flex align-items-center">
                    <span class="sidebar-icon">
                        <img src="{{ asset('static/images/logo.svg') }}" height="20" width="20" alt="Volt Logo">
                    </span>
                    <span class="mt-1 ms-1 sidebar-text">Home</span>
                </a>
            </li>
            <li class="nav-item {{ Route::is('admin.home', 'student.home', 'student.index') ? 'active' : '' }}">
                <a href="{{ route('root.home') }}" class="nav-link">
                    <span class="material-icons sidebar-icon">
                        dashboard
                    </span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>

            @if (Auth::user()->can('view', 'App\\Models\User'))
                <li class="nav-item {{ Route::is('users.show') ? 'active' : '' }}">
                    <a href="{{ route('users.show') }}" class="nav-link">
                        <span class="material-icons sidebar-icon">
                            admin_panel_settings
                        </span>
                        <span class="sidebar-text">Manage</span>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a href="#!" class="nav-link">
                    <span class="material-icons sidebar-icon text-gray-500">
                        forum
                    </span>
                    <span class="sidebar-text text-gray-500">Conversations</span>
                </a>
            </li>

            @if (Auth::user()->hasRole('student'))
                <li role="separator" class="dropdown-divider my-3 border-gray-600"></li>

                <li class="nav-item">
                    <a href="{{ route('student.index') }}" class="nav-link" title="Information">
                        <span class="material-icons sidebar-icon">
                            switch_account
                        </span>
                        <span class="sidebar-text">Student Account</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#!" class="nav-link" title="Semester Registration">
                        <span class="material-icons sidebar-icon">
                            app_registration
                        </span>
                        <span class="sidebar-text">Registration</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#!" class="nav-link">
                        <span class="material-icons sidebar-icon">
                            history_edu
                        </span>
                        <span class="sidebar-text">Results</span>
                    </a>
                </li>
            @endif

            {{-- <li class="nav-item">
                <a href="#!" class="nav-link d-flex justify-content-between">
                    <span>
                        <span class="material-icons sidebar-icon">
                            home
                        </span>
                        <span class="sidebar-text">With Badge</span>
                    </span>
                    <span>
                        <span class="badge badge-sm bg-secondary ms-1 text-gray-800">Pro</span>
                    </span>
                </a>
            </li> --}}
            {{-- <li class="nav-item ">
                <a href="#!" class="nav-link">
                    <span class="material-icons sidebar-icon">
                        delete
                    </span>
                    <span class="sidebar-text">Normal Item</span>
                </a>
            </li> --}}

            @if (Auth::user()->hasRole('root', 'admin', 'office', 'hod', 'ecell', 'faculty', 'staff'))
                <li role="separator" class="dropdown-divider my-3 border-gray-600"></li>

                {{-- <li class="nav-item">Quick Links</li> --}}

                <li class="nav-item">
                    <span class="nav-link collapsed d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-homepage">
                        <span>
                            <span class="material-icons sidebar-icon">
                                meeting_room
                            </span>
                            <span class="sidebar-text">Office</span>
                        </span>
                        <span class="link-arrow">
                            @include('partials.sidemenuSvg')
                        </span>
                    </span>
                    <div class="multi-level collapse" role="list" id="submenu-homepage" aria-expanded="false">
                        <ul class="flex-column nav">
                            <li class="nav-item
                                {{ Route::is('admin.homepage.notification.show', 'admin.homepage.notification.showTrashed')
                                    ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.homepage.notification.show') }}">
                                    <span class="sidebar-text-contracted">N</span>
                                    <span class="sidebar-text">Notifications</span>
                                </a>
                            </li>
                            <li class="nav-item
                                {{ Route::is('admin.office.hods.show') ? 'active' : 'hod' }}">
                                <a class="nav-link" href="{{ route('admin.office.hods.show') }}">
                                    <span class="sidebar-text-contracted">H</span>
                                    <span class="sidebar-text">HoD's</span>
                                </a>
                            </li>
                            <li class="nav-item
                                {{ Route::is('admin.office.positions.show') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.office.positions.show') }}">
                                    <span class="sidebar-text-contracted">P</span>
                                    <span class="sidebar-text">PoR's</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ Route::is('admin.department.select') ? 'active' : '' }}">
                    <span class="nav-link  collapsed  d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-department">
                        <span>
                            <span class="material-icons sidebar-icon">
                                business
                            </span>
                            <span class="sidebar-text">Departments</span>
                        </span>
                        <span class="link-arrow">
                            @include('partials.sidemenuSvg')
                        </span>
                    </span>

                    @php
                        $routeUrl = $isDeptSelected ? route('admin.department.home', $department) : route('admin.department.index');
                    @endphp

                    <div class="multi-level collapse " role="list" id="submenu-department" aria-expanded="false">
                        <ul class="flex-column nav">
                            <li class="nav-item
                                {{ Route::is('admin.department.home') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ $routeUrl }}">
                                    <span class="sidebar-text-contracted">H</span>
                                    <span class="sidebar-text">Home</span>
                                </a>
                            </li>
                            <li class="nav-item {{ Route::is('admin.department.show') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.department.show') }}">
                                    <span class="sidebar-text-contracted">L</span>
                                    <span class="sidebar-text">List</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <span class="nav-link collapsed d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-people">
                        <span>
                            <span class="material-icons sidebar-icon">
                                people
                            </span>
                            <span class="sidebar-text">People</span>
                        </span>
                        <span class="link-arrow">
                            @include('partials.sidemenuSvg')
                        </span>
                    </span>
                    <div class="multi-level collapse" role="list" id="submenu-people" aria-expanded="false">
                        <ul class="flex-column nav">
                            <li class="nav-item {{ Route::is('admin.profiles.show') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.profiles.show') }}">
                                    <span class="sidebar-text-contracted">F</span>
                                    <span class="sidebar-text">Faculty / Staff</span>
                                </a>
                            </li>
                            <li class="nav-item {{ false ? 'active' : '' }}">
                                <a class="nav-link" href="#!">
                                    <span class="sidebar-text-contracted">R</span>
                                    <span class="sidebar-text">Researchers</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <span class="nav-link collapsed d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-students">
                        <span>
                            <span class="material-icons sidebar-icon">
                                face
                            </span>
                            <span class="sidebar-text">Students</span>
                        </span>
                        <span class="link-arrow">
                            @include('partials.sidemenuSvg')
                        </span>
                    </span>
                    <div class="multi-level collapse" role="list" id="submenu-students" aria-expanded="false">
                        <ul class="flex-column nav">

                            @php
                                $routeUrl = ($isDeptSelected && $isBatchSelected)
                                    ? route('admin.students.show', [
                                            'dept' => $department,
                                            'batch' => $batch
                                        ])
                                    : route('admin.students.handleRedirect');
                            @endphp

                            <li class="nav-item {{ Route::is('admin.students.show') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ $routeUrl }}">
                                    <span class="sidebar-text-contracted">L</span>
                                    <span class="sidebar-text">List</span>
                                </a>
                            </li>
                            <li class="nav-item {{ Route::is('admin.students.searchForm', 'admin.students.search') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.students.searchForm') }}">
                                    <span class="sidebar-text-contracted">F</span>
                                    <span class="sidebar-text">Find</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <span class="nav-link  collapsed  d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-results">
                        <span>
                            <span class="material-icons sidebar-icon">
                                military_tech
                            </span>
                            <span class="sidebar-text">Results</span>
                        </span>
                        <span class="link-arrow">
                            @include('partials.sidemenuSvg')
                        </span>
                    </span>
                    <div class="multi-level collapse " role="list" id="submenu-results" aria-expanded="false">
                        <ul class="flex-column nav">

                            @php
                                $routeUrl = ($isDeptSelected && $isBatchSelected && $isSubjectSelected)
                                    ? route('admin.results.show', [
                                            'dept' => $department,
                                            'batch' => $batch,
                                            'subject' => $subject
                                        ])
                                    : route('admin.results.handleRedirect');
                            @endphp

                            <li class="nav-item {{ Route::is('admin.results.show') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ $routeUrl }}">
                                    <span class="sidebar-text-contracted">V</span>
                                    <span class="sidebar-text">View</span>
                                </a>
                            </li>

                            @php
                                $routeUrl = ($isDeptSelected && $isBatchSelected)
                                    ? route('admin.results.showSemWise', [
                                        'dept' => $department,
                                        'batch' => $batch
                                    ])
                                    : route('admin.results.semWiseHandleRedirect')
                            @endphp

                            <li class="nav-item {{ Route::is('admin.results.showSemWise') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ $routeUrl }}">
                                    <span class="sidebar-text-contracted">S</span>
                                    <span class="sidebar-text">Semester Wise</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <span class="nav-link  collapsed  d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-tnp">
                        <span>
                            <span class="material-icons sidebar-icon text-gray-500">
                                contacts
                            </span>
                            <span class="sidebar-text text-gray-500">TnP</span>
                        </span>
                        <span class="link-arrow">
                            @include('partials.sidemenuSvg')
                        </span>
                    </span>
                    <div class="multi-level collapse" role="list" id="submenu-tnp" aria-expanded="false">
                        <ul class="flex-column nav">
                            <li class="nav-item">
                                <a class="nav-link text-gray-500 disabled" href="#!">
                                    <span class="sidebar-text-contracted">U</span>
                                    <span class="sidebar-text">Under Dev.</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ Route::is('admin.batch.select') ? 'active' : '' }}">
                    <span class="nav-link  collapsed  d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-batch">
                        <span>
                            <span class="material-icons sidebar-icon">
                                format_list_numbered
                            </span>
                            <span class="sidebar-text">Batches</span>
                        </span>
                        <span class="link-arrow">
                            @include('partials.sidemenuSvg')
                        </span>
                    </span>
                    <div class="multi-level collapse " role="list" id="submenu-batch" aria-expanded="false">
                        <ul class="flex-column nav">
                            <li class="nav-item {{ Route::is('admin.batch.show') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.batch.show') }}">
                                    <span class="sidebar-text-contracted">L</span>
                                    <span class="sidebar-text">List</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ Route::is('admin.subjects.select') ? 'active' : '' }}">
                    <span class="nav-link  collapsed  d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-subjects">
                        <span>
                            <span class="material-icons sidebar-icon">
                                auto_stories
                            </span>
                            <span class="sidebar-text">Subjects</span>
                        </span>
                        <span class="link-arrow">
                            @include('partials.sidemenuSvg')
                        </span>
                    </span>
                    <div class="multi-level collapse " role="list" id="submenu-subjects" aria-expanded="false">

                        @php
                            $routeUrl = ($isDeptSelected)
                                ? route('admin.subjects.show', [
                                        'dept' => $department
                                    ])
                                : route('admin.subjects.show');
                        @endphp

                        <ul class="flex-column nav">
                            <li class="nav-item {{ Route::is('admin.subjects.show') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ $routeUrl }}">
                                    <span class="sidebar-text-contracted">L</span>
                                    <span class="sidebar-text">List</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            <li role="separator" class="dropdown-divider my-3 border-gray-600"></li>

            <li class="nav-item mb-3">
                <a href="{{ route('logout') }}" class="nav-link d-flex align-items-center"
                    confirm alert-title='Logout?' alert-text='Your session data will be cleared'>
                    <span class="material-icons sidebar-icon">
                        exit_to_app
                    </span>
                    <span class="sidebar-text">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
