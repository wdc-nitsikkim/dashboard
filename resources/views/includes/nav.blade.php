<nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-light ps-0 pe-2 pb-0">
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
            <div class="d-flex align-items-center">
                {{-- <button id="sidebar-toggle"
                    class="sidebar-toggle me-3 btn btn-icon-only d-none d-lg-inline-block align-items-center justify-content-center">
                    <span class="material-icons">menu</span>
                </button> --}}
                <!-- Search form -->
                <form class="navbar-search form-inline" id="navbar-search-main">
                    <div class="input-group input-group-merge search-bar">
                        <span class="input-group-text" id="topbar-addon">
                            <svg class="icon icon-xs" x-description="Heroicon name: solid/search"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <input type="text" class="form-control" id="topbarInputIconLeft" placeholder="Search"
                            aria-label="Search" aria-describedby="topbar-addon">
                    </div>
                </form>
                <!-- / Search form -->
            </div>
            <!-- Navbar links -->

            @php
                $unread = session()->has('status') || $errors->any();
            @endphp

            <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link text-dark notification-bell {{ $unread ? 'unread unread-animation' : '' }}
                        dropdown-toggle"
                        data-unread-notifications="{{ $unread ? 'true' : 'false' }}"
                        href="#" role="button" data-bs-toggle="dropdown" data-bs-display="static"
                        aria-expanded="false">
                        <span class="material-icons">
                            notifications
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-center mt-2 py-0">
                        <div class="list-group list-group-flush">
                            <a class="text-center text-primary fw-bold border-bottom border-light py-3">
                                {{ $unread ? 'Recent Notifications' : 'No notifications!' }}</a>

                            @if (session()->has('status'))
                                @php
                                    $tmp_noti['class'] = 'text-success';
                                    $tmp_noti['icon'] = 'task_alt';
                                    if (session('status') != 'success') {
                                        $tmp_noti['class'] = 'text-danger';
                                        $tmp_noti['icon'] = 'error_outline';
                                    }
                                @endphp

                                <a href="#" class="list-group-item list-group-item-action border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <!-- Avatar -->
                                            <span class="material-icons {{ $tmp_noti['class'] }}">
                                                {{ $tmp_noti['icon'] }}
                                            </span>
                                        </div>
                                        <div class="col ps-0 ms-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h4 class="h6 mb-0 text-small {{ $tmp_noti['class'] }}">
                                                        {{ ucfirst(session('status')) }}</h4>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-danger">a few moments ago</small>
                                                </div>
                                            </div>
                                            <p class="font-small mt-1 mb-0">{{ session('message') }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endif

                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <a href="#" class="list-group-item list-group-item-action border-bottom">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <!-- Avatar -->
                                                <span class="material-icons text-danger">
                                                    error
                                                </span>
                                            </div>
                                            <div class="col ps-0 ms-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h4 class="h6 mb-0 text-small text-primary">
                                                            Form Error!</h4>
                                                    </div>
                                                    <div class="text-end">
                                                        <small class="text-primary">a few moments ago</small>
                                                    </div>
                                                </div>
                                                <p class="font-small mt-1 mb-0">{{ $error }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @endif

                            {{-- <a href="#" class="dropdown-item text-center fw-bold rounded-bottom py-3">
                                <span class="material-icons mx-1">
                                    visibility
                                </span>
                                View all
                            </a> --}}
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle pt-1 px-0" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="media d-flex align-items-center">
                            <img class="avatar rounded-circle" alt="Image placeholder"
                                src="{{ asset('static/images/admin.webp') }}">
                            <div class="media-body ms-2 text-dark align-items-center d-none d-lg-block">
                                <span class="badge bg-info text-dark mx-1">{{ strtolower(Auth::user()->email) ?? '-' }}</span>
                                <span class="mb-0 font-small fw-bold text-gray-900">{{ Auth::user()->name ?? '-' }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dashboard-dropdown dropdown-menu-end mt-2 py-1">
                        <a class="dropdown-item d-flex align-items-center"
                            href="{{ route('users.profile', Auth::id()) }}">
                            <span class="material-icons">account_circle</span>
                            My Account
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#!">
                            <span class="material-icons">settings</span>
                            Settings
                        </a>
                        <a class="dropdown-item d-flex align-items-center"
                            href="{{ route('root.clearSession') }}" confirm alert-title="Clear Session?"
                            alert-text="All session data will be cleared!" alert-timer="5000" spoof spoof-method="POST">
                            <span class="material-icons">close</span>
                            Session
                        </a>
                        <div role="separator" class="dropdown-divider my-1"></div>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
                            <span class="text-danger material-icons">exit_to_app</span>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
