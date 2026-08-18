<header class="navbar-header">
    <div class="page-container topbar-menu">
        <div class="d-flex align-items-center gap-2">
            <a id="mobile_btn" class="mobile-btn" href="#sidebar">
            <i class="ti ti-menu-deep fs-24"></i>
            </a>
            <a href="{{ route('user.dashboard') }}" class="logo">
                <span class="logo-light">
                <span class="logo-lg"><img src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_logo) }}" style="height: 29px" alt="logo"></span>
                </span>
                <span class="logo-dark">
                <span class="logo-lg"><img src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_logo) }}" style="height: 29px" alt="dark logo"></span>
                </span>
                <span class="logo-small">
                <span class="logo-lg"><img src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_logo) }}" style="height: 29px" alt="small logo"></span>
                </span>
            </a>
            <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn2">
                <i class="ti ti-arrow-bar-to-right"></i>
            </button>
            <div class="me-auto d-flex align-items-center header-search d-lg-flex d-none">
                <div class="d-flex align-items-center gap-2 border rounded bg-light px-3 py-2 me-2" style="min-width: 190px;">
                    <span class="avatar avatar-sm rounded bg-primary text-white d-inline-flex align-items-center justify-content-center">
                        <i class="ti ti-calendar-event fs-16"></i>
                    </span>
                    <div class="lh-sm">
                        <span class="d-block fs-12 text-muted">Today</span>
                        <span class="fw-semibold text-dark">{{ date('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <div class="me-auto d-flex align-items-center header-search d-lg-none d-sm-flex d-none">
                <div class="d-flex align-items-center gap-2 border rounded bg-light px-3 py-2 me-2" style="min-width: 190px;">
                    <span class="avatar avatar-sm rounded bg-primary text-white d-inline-flex align-items-center justify-content-center">
                        <i class="ti ti-calendar-event fs-16"></i>
                    </span>
                    <div class="lh-sm">
                        <span class="d-block fs-12 text-muted">Today</span>
                        <span class="fw-semibold text-dark">{{ date('d M Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="header-item">
                <div class="dropdown me-2">
                    <a href="javascript:void(0);" class="btn topbar-link btnFullscreen" aria-label="minimize"><i class="ti ti-minimize"></i></a>
                </div>
            </div>
            {{-- <div class="header-item">
                <div class="dropdown me-2">
                    <button class="topbar-link btn topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown" data-bs-offset="0,24" type="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-bell-check fs-16 animate-ring"></i>
                        <span class="notification-badge"></span>
                    </button>
                    <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg" style="min-height: 300px;">
                        <div class="p-2 border-bottom">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fs-16 fw-semibold"> Notifications</h6>
                                </div>
                            </div>
                        </div>
                        <div class="notification-body position-relative z-2 rounded-0" data-simplebar>
                            <div class="dropdown-item notification-item py-3 text-wrap border-bottom" id="notification-1">
                                <div class="d-flex">
                                    <div class="me-2 position-relative flex-shrink-0">
                                        <img src="{{ asset(config('constants.admin_path').'img/avatars/avatar-28.jpg') }}" class="avatar-md rounded-circle" alt="patient">
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-medium text-dark">John Doe</p>
                                        <p class="mb-1 text-wrap">
                                            added new  patient <span class="fw-medium text-dark">appointment booking.</span>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fs-12"><i class="ti ti-clock me-1"></i>4 min ago</span>
                                            <div class="notification-action d-flex align-items-center float-end gap-2">
                                                <a href="javascript:void(0);" class="notification-read rounded-circle bg-danger" data-bs-toggle="tooltip" title="" data-bs-original-title="Make as Read" aria-label="Make as Read"></a>
                                                <button class="btn rounded-circle p-0" data-dismissible="#notification-1">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-item notification-item py-3 text-wrap border-bottom" id="notification-2">
                                <div class="d-flex">
                                    <div class="me-2 position-relative flex-shrink-0">
                                        <img src="{{ asset(config('constants.admin_path').'img/avatars/avatar-39.jpg') }}" class="avatar-md rounded-circle" alt="patient">
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-medium text-dark">Thomas William</p>
                                        <p class="mb-1 text-wrap">
                                            booked a new appointment
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fs-12"><i class="ti ti-clock me-1"></i>8 min ago</span>
                                            <div class="notification-action d-flex align-items-center float-end gap-2">
                                                <a href="javascript:void(0);" class="notification-read rounded-circle bg-danger" data-bs-toggle="tooltip" title="" data-bs-original-title="Make as Read" aria-label="Make as Read"></a>
                                                <button class="btn rounded-circle p-0" data-dismissible="#notification-2">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-item notification-item py-3 text-wrap border-bottom" id="notification-3">
                                <div class="d-flex">
                                    <div class="me-2 position-relative flex-shrink-0">
                                        <img src="{{ asset(config('constants.admin_path').'img/avatars/avatar-12.jpg') }}" class="avatar-md rounded-circle" alt="patient">
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-medium text-dark">Sarah Anderson</p>
                                        <p class="mb-1 text-wrap">
                                            has been successfully booked for <span class="fw-medium text-dark">April 5 at 10:00 AM.</span>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fs-12"><i class="ti ti-clock me-1"></i>15 min ago</span>
                                            <div class="notification-action d-flex align-items-center float-end gap-2">
                                                <a href="javascript:void(0);" class="notification-read rounded-circle bg-danger" data-bs-toggle="tooltip" title="" data-bs-original-title="Make as Read" aria-label="Make as Read"></a>
                                                <button class="btn rounded-circle p-0" data-dismissible="#notification-3">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-item notification-item py-3 text-wrap" id="notification-4">
                                <div class="d-flex">
                                    <div class="me-2 position-relative flex-shrink-0">
                                        <img src="{{ asset(config('constants.admin_path').'img/avatars/avatar-08.jpg') }}" class="avatar-md rounded-circle" alt="patient">
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-medium text-dark">Ann McClure</p>
                                        <p class="mb-1 text-wrap">
                                            cancelled her appointment scheduled for <span class="fw-medium text-dark">February 5, 2024</span>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fs-12"><i class="ti ti-clock me-1"></i>20 min ago</span>
                                            <div class="notification-action d-flex align-items-center float-end gap-2">
                                                <a href="javascript:void(0);" class="notification-read rounded-circle bg-danger" data-bs-toggle="tooltip" title="" data-bs-original-title="Make as Read" aria-label="Make as Read"></a>
                                                <button class="btn rounded-circle p-0" data-dismissible="#notification-4">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 rounded-bottom border-top text-center">
                            <a href="javascript:void(0)" class="text-center text-decoration-underline fs-14 mb-0">
                                View All Notifications
                            </a>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="header-item d-flex me-2">
                <button class="topbar-link btn topbar-link" id="light-dark-mode" type="button">
                    <i class="ti ti-moon fs-16"></i>
                </button>
            </div>
            <div class="dropdown profile-dropdown d-flex align-items-center justify-content-center">
                <a href="javascript:void(0);" class="topbar-link dropdown-toggle drop-arrow-none position-relative" data-bs-toggle="dropdown" data-bs-offset="0,22" aria-haspopup="false" aria-expanded="false">
                    @if(!empty(Auth::user()->user_image))
                    <img src="{{ asset(config('constants.admin_path').'uploads/profile/'.Auth::user()->user_image) }}" width="32" class="rounded-2 d-flex" alt="">
                    @else
                    <img src="{{ asset(config('constants.admin_path').'img/no_image.png') }}" width="32" class="rounded-2 d-flex" alt="">
                    @endif
                    <span class="online text-success"><i class="ti ti-circle-filled d-flex bg-white rounded-circle border border-1 border-white"></i></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">
                    <div class="d-flex align-items-center bg-light rounded p-2 mb-2">
                        @if(!empty(Auth::user()->user_image))
                        <img src="{{ asset(config('constants.admin_path').'uploads/profile/'.Auth::user()->user_image) }}" class="rounded-circle" width="42" height="42" alt="">
                        @else
                        <img src="{{ asset(config('constants.admin_path').'img/no_image.png') }}" class="rounded-circle" width="42" height="42" alt="">
                        @endif
                        <div class="ms-2">
                        <p class="fw-medium text-dark mb-0">{{ Auth::user()->user_name }}</p>
                        <span class="d-block fs-13">{{ Request()->role->role_name }}</span>
                        </div>
                    </div>
                    <a href="{{ route('user.profile') }}" class="dropdown-item">
                        <i class="ti ti-user-circle me-1 align-middle"></i>
                        <span class="align-middle">Profile</span>
                    </a>
                    @if(Helper::userHasPermission('user.settings'))
                    <a href="{{ route('user.settings') }}" class="dropdown-item">
                        <i class="ti ti-settings me-1 align-middle"></i>
                        <span class="align-middle">Settings</span>
                    </a>
                    @endif
                    <div class="pt-2 mt-2 border-top">
                        <a href="{{ route('user.logout') }}" class="dropdown-item text-danger">
                        <i class="ti ti-logout me-1 fs-17 align-middle"></i>
                        <span class="align-middle">Sign Out</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
