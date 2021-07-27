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

<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
    {{-- visible in mobile view --}}
    <div class="sidebar-inner px-4 pt-3">
        <div
            class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
            <div class="d-flex align-items-center">
                <div class="avatar-lg me-4">
                    <img src="{{ asset('static/images/admin.webp') }}"
                        class="card-img-top rounded-circle border-white" alt="Bonnie Green">
                </div>
                <div class="d-block">
                    <h2 class="h5 mb-3">Hi, {{ Auth::user()->name }}</h2>
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
            <li class="nav-item {{ url()->current() == url('/default') ? 'active' : '' }}">
                <a href="{{ url('/default') }}" class="nav-link">
                    <span class="material-icons sidebar-icon">
                        dashboard
                    </span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
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

            <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-600"></li>

            {{-- <li class="nav-item">Quick Links</li> --}}

            <li class="nav-item">
                <span class="nav-link collapsed d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#submenu-homepage">
                    <span>
                        <span class="material-icons sidebar-icon">
                            home
                        </span>
                        <span class="sidebar-text">Homepage</span>
                    </span>
                    <span class="link-arrow">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </span>
                <div class="multi-level collapse" role="list" id="submenu-homepage" aria-expanded="false">
                    <ul class="flex-column nav">
                        <li class="nav-item {{ Route::is('homepage.notification.show') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('homepage.notification.show') }}">
                                <span class="sidebar-text">Notifications</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('homepage.notification.showTrashed') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('homepage.notification.showTrashed') }}">
                                <span class="sidebar-text">Trash</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <span class="nav-link  collapsed  d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#submenu-department">
                    <span>
                        <span class="material-icons sidebar-icon">
                            business
                        </span>
                        <span class="sidebar-text">Department</span>
                    </span>
                    <span class="link-arrow">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </span>
                <div class="multi-level collapse " role="list" id="submenu-department" aria-expanded="false">
                    <ul class="flex-column nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#!">
                                <span class="sidebar-text">Home</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#!">
                                <span class="sidebar-text">Students</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#!">
                                <span class="sidebar-text">Timetable & Syllabus</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#!">
                                <span class="sidebar-text">Faculty & Staff</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#!">
                                <span class="sidebar-text">Researchers</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-600"></li>

            <li class="nav-item">
                <a href="#!" class="nav-link d-flex align-items-center">
                    <span class="material-icons sidebar-icon">
                        help
                    </span>
                    <span class="sidebar-text">Help {{-- <span
                            class="badge badge-sm bg-secondary ms-1 text-gray-800">New</span> --}}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('logout') }}" class="btn btn-danger justify-content-center btn-upgrade-pro">
                    <span class="material-icons sidebar-icon">
                        exit_to_app
                    </span>
                    <span class="sidebar-text">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
