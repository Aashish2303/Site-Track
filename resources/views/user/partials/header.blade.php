<header class="navbar-header">
    <div class="page-container topbar-menu">
        <div class="st-topbar-left">
            <button type="button" id="mobile_btn" class="mobile-btn" aria-label="Open navigation" aria-controls="sidebar" aria-expanded="false"><i class="ti ti-menu-2" aria-hidden="true"></i></button>
            <div class="st-breadcrumb"><span>{{ ($inProjectWorkspace ?? false) ? 'Project workspace' : 'Global overview' }}</span><i class="ti ti-chevron-right" aria-hidden="true"></i><strong>{{ \Illuminate\Support\Str::before(trim($__env->yieldContent('title')), ' - ') }}</strong></div>
        </div>
        <div class="st-topbar-right">
            <button type="button" class="btn st-search-trigger" id="st-command-toggle" aria-label="Search workspace navigation" aria-haspopup="dialog" aria-controls="st-command-menu"><i class="ti ti-search" aria-hidden="true"></i><span>Find a page...</span><kbd>⌘ K</kbd></button>
            <button type="button" class="topbar-link btn header-fullscreen btnFullscreen" aria-label="Toggle fullscreen"><i class="ti ti-maximize" aria-hidden="true"></i></button>
            <button class="topbar-link btn" id="light-dark-mode" type="button" aria-label="Switch color theme"><i class="ti ti-sun-moon" aria-hidden="true"></i></button>
            <div class="dropdown profile-dropdown">
                <button type="button" class="topbar-link st-profile-trigger dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu for {{ Auth::user()->user_name }}">
                    <span class="st-profile-avatar">
                        @if(!empty(Auth::user()->user_image))
                        <img src="{{ asset(config('constants.admin_path').'uploads/profile/'.Auth::user()->user_image) }}" alt="">
                        @else
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(Auth::user()->user_name, 0, 1)) }}
                        @endif
                    </span>
                    <span class="st-profile-copy"><strong>{{ Auth::user()->user_name }}</strong><small>{{ Request()->role->role_name }}</small></span>
                    <i class="ti ti-chevron-down fs-12" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">
                    <div class="px-2 py-2 mb-2 border-bottom"><p class="mb-0 fw-semibold">{{ Auth::user()->user_name }}</p><span class="text-muted fs-12">{{ Request()->role->role_name }}</span></div>
                    <a href="{{ route('user.profile') }}" class="dropdown-item"><i class="ti ti-user-circle me-2" aria-hidden="true"></i>My profile</a>
                    @if(Helper::userHasPermission('user.settings'))
                    <a href="{{ route('user.settings') }}" class="dropdown-item"><i class="ti ti-settings me-2" aria-hidden="true"></i>Settings</a>
                    @endif
                    <div class="pt-2 mt-2 border-top"><a href="{{ route('user.logout') }}" class="dropdown-item text-danger"><i class="ti ti-logout me-2" aria-hidden="true"></i>Sign out</a></div>
                </div>
            </div>
        </div>
    </div>
</header>
<dialog id="st-command-menu" class="st-command" aria-labelledby="st-command-title">
    <h2 id="st-command-title" class="visually-hidden">Search workspace navigation</h2>
    <div class="st-command-head"><i class="ti ti-search" aria-hidden="true"></i><label class="visually-hidden" for="st-command-input">Find a page</label><input id="st-command-input" type="search" placeholder="Where would you like to go?" autocomplete="off"><button type="button" data-st-close aria-label="Close navigation search">Esc</button></div>
    <nav class="st-command-list" id="st-command-results" aria-label="Matching pages"></nav>
    <div class="st-command-foot"><span>↑ ↓ to move &nbsp; · &nbsp; Enter to open</span><span>Workspace navigation</span></div>
</dialog>
