<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div>
            <a href="{{ route('user.dashboard') }}" class="logo logo-normal">
                <img src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_wlogo) }}" style="height: 40px" alt="Logo">
            </a>
            <a href="{{ route('user.dashboard') }}" class="logo-small">
                <img src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_favicon) }}" style="height: 40px" alt="Logo">
            </a>
            <a href="{{ route('user.dashboard') }}" class="dark-logo">
                <img src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_logo) }}" style="height: 40px" alt="Logo">
            </a>
        </div>
        <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn">
        <i class="ti ti-arrow-bar-to-left"></i>
        </button>
        <button class="sidebar-close">
        <i class="ti ti-x align-middle"></i>
        </button>
    </div>
    <div class="sidebar-inner" data-simplebar>
        <div id="sidebar-menu" class="sidebar-menu">
            <ul role="menu" aria-label="Main navigation menu">
                <li class="menu-title" aria-disabled="true"><span>MAIN</span></li>
                <li>
                    <a href="{{ route('user.dashboard') }}" @if(isset($set) && $set == 'dashboard')class="active"@endif>
                    <i class="ti ti-layout-board"></i><span>Dashboard</span>
                    </a>
                </li>
                @if(Helper::userHasPermission('user.projects') || Helper::userHasPermission('user.clients'))
                <li class="submenu">
                    <a href="javascript:void(0)" @if(isset($set) && $set == 'projects' || $set == 'clients') class="active subdrop"@endif>
                        <i class="ti ti-building"></i><span>Projects</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul @if(isset($set) && $set == 'projects' || $set == 'clients') style="display: block;" @endif>
                        @if(Helper::userHasPermission('user.projects'))
                        <li><a href="{{ route('user.projects') }}" @if(isset($set) && $set == 'projects') class="active" @endif>Projects</a></li>
                        @endif
                        @if(Helper::userHasPermission('user.clients'))
                        <li><a href="{{ route('user.clients') }}" @if(isset($set) && $set == 'clients') class="active" @endif>Clients</a></li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(Helper::userHasPermission('user.daily_works'))
                <li>
                    <a href="{{ route('user.daily_works') }}" @if(isset($set) && $set == 'daily_works')class="active"@endif>
                    <i class="ti ti-calendar"></i><span>Daily Works</span>
                    </a>
                </li>
                @endif
                @if(Helper::userHasPermission('user.daily_progress'))
                <li>
                    <a href="{{ route('user.daily_progress') }}" @if(isset($set) && $set == 'daily_progress')class="active"@endif>
                    <i class="ti ti-whirl"></i><span>Daily Progress</span>
                    </a>
                </li>
                @endif
                @if(Helper::userHasPermission('user.boq_tracker'))
                <li>
                    <a href="{{ route('user.boq_tracker') }}" @if(isset($set) && $set == 'boq_tracker')class="active"@endif>
                    <i class="ti ti-layout-kanban"></i><span>BOQ Tracker</span>
                    </a>
                </li>
                @endif
                @if(Helper::userHasPermission('user.assign_materials') || Helper::userHasPermission('user.return_materials') || Helper::userHasPermission('user.materials') || Helper::userHasPermission('user.orders') || Helper::userHasPermission('user.stocks') || Helper::userHasPermission('user.material_category'))
                <li class="submenu">
                    <a href="javascript:void(0)" @if(isset($set) && $set == 'materials' || $set == 'assign_materials' || $set == 'return_materials' || $set == 'orders' || $set == 'stocks' || $set == 'material_category') class="active subdrop"@endif>
                        <i class="ti ti-package"></i><span>Materials</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul @if(isset($set) && $set == 'materials' || $set == 'assign_materials' || $set == 'return_materials' || $set == 'orders' || $set == 'stocks' || $set == 'material_category') style="display: block;" @endif>
                        @if(Helper::userHasPermission('user.assign_materials'))
                        <li><a href="{{ route('user.assign_materials') }}" @if(isset($set) && $set == 'assign_materials') class="active" @endif>Assign Materials</a></li>
                        @endif
                        @if(Helper::userHasPermission('user.return_materials'))
                        <li><a href="{{ route('user.return_materials') }}" @if(isset($set) && $set == 'return_materials') class="active" @endif>Return Materials</a></li>
                        @endif
                        @if(Helper::userHasPermission('user.orders'))
                        <li><a href="{{ route('user.orders') }}" @if(isset($set) && $set == 'orders') class="active" @endif>Orders</a></li>
                        @endif
                        @if(Helper::userHasPermission('user.stocks'))
                        <li><a href="{{ route('user.stocks') }}" @if(isset($set) && $set == 'stocks') class="active" @endif>Stocks</a></li>
                        @endif
                        @if(Helper::userHasPermission('user.materials'))
                        <li><a href="{{ route('user.materials') }}" @if(isset($set) && $set == 'materials') class="active" @endif>Materials</a></li>
                        @endif
                        @if(Helper::userHasPermission('user.material_category'))
                        <li><a href="{{ route('user.material_category') }}" @if(isset($set) && $set == 'material_category') class="active" @endif>Category</a></li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(Helper::userHasPermission('user.indents'))
                <li>
                    <a href="{{ route('user.indents') }}" @if(isset($set) && $set == 'indents')class="active"@endif>
                    <i class="ti ti-clipboard"></i><span>Indents</span>
                    </a>
                </li>
                @endif
                @if(Helper::userHasPermission('user.labour_trackers'))
                <li>
                    <a href="{{ route('user.labour_trackers') }}" @if(isset($set) && $set == 'labour_trackers')class="active"@endif>
                    <i class="ti ti-users-group"></i><span>Labour Tracker</span>
                    </a>
                </li>
                @endif
                @if(Helper::userHasPermission('user.issues') || Helper::userHasPermission('user.issue_category'))
                <li class="submenu">
                    <a href="javascript:void(0)" @if(isset($set) && $set == 'issues' || $set == 'issue_category') class="active subdrop"@endif>
                        <i class="ti ti-alert-triangle"></i><span>Issues</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul @if(isset($set) && $set == 'issues' || $set == 'issue_category') style="display: block;" @endif>
                        @if(Helper::userHasPermission('user.issues'))
                        <li><a href="{{ route('user.issues') }}" @if(isset($set) && $set == 'issues') class="active" @endif>Issues</a></li>
                        @endif
                        @if(Helper::userHasPermission('user.issue_category'))
                        <li><a href="{{ route('user.issue_category') }}" @if(isset($set) && $set == 'issue_category') class="active" @endif>Category</a></li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(Helper::userHasPermission('user.subcontractor_ledger') || Helper::userHasPermission('user.vendor_ledger'))
                <li class="submenu">
                    <a href="javascript:void(0)" @if(isset($set) && $set == 'subcontractor_ledger' || $set == 'vendor_ledger') class="active subdrop"@endif>
                        <i class="ti ti-book"></i><span>Ledger</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul @if(isset($set) && $set == 'subcontractor_ledger' || $set == 'vendor_ledger') style="display: block;" @endif>
                        @if(Helper::userHasPermission('user.subcontractor_ledger'))
                        <li><a href="{{ route('user.subcontractor_ledger') }}" @if(isset($set) && $set == 'subcontractor_ledger') class="active" @endif>Subcontractor</a></li>
                        @endif
                        @if(Helper::userHasPermission('user.vendor_ledger'))
                        <li><a href="{{ route('user.vendor_ledger') }}" @if(isset($set) && $set == 'vendor_ledger') class="active" @endif>Vendor</a></li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(Helper::userHasPermission('user.vendors'))
                <li>
                    <a href="{{ route('user.vendors') }}" @if(isset($set) && $set == 'vendors')class="active"@endif>
                    <i class="ti ti-user"></i><span>Vendors</span>
                    </a>
                </li>
                @endif
                @if(Helper::userHasPermission('user.subcontractors'))
                <li>
                    <a href="{{ route('user.subcontractors') }}" @if(isset($set) && $set == 'subcontractors')class="active"@endif>
                    <i class="ti ti-briefcase"></i><span>Subcontractors</span>
                    </a>
                </li>
                @endif
                @if(Helper::userHasPermission('user.category'))
                <li>
                    <a href="{{ route('user.category') }}" @if(isset($set) && $set == 'category')class="active"@endif>
                    <i class="ti ti-layout-grid"></i><span>Work Category</span>
                    </a>
                </li>
                @endif
                @if(Helper::userHasPermission('user.units'))
                <li>
                    <a href="{{ route('user.units') }}" @if(isset($set) && $set == 'units')class="active"@endif>
                    <i class="ti ti-ruler"></i><span>Units</span>
                    </a>
                </li>
                @endif
                @if(Helper::userHasPermission('user.qms'))
                <li>
                    <a href="{{ route('user.qms') }}" @if(isset($set) && $set == 'qms')class="active"@endif>
                    <i class="ti ti-files"></i><span>QMS Reports</span>
                    </a>
                </li>
                @endif
                @if(Helper::userHasPermission('user.users') || Helper::userHasPermission('user.roles'))
                <li class="submenu">
                    <a href="javascript:void(0)" @if(isset($set) && $set == 'users' || $set == 'roles') class="active subdrop"@endif>
                        <i class="ti ti-lock"></i><span>Roles & Permissions</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul @if(isset($set) && $set == 'users' || $set == 'roles') style="display: block;" @endif>
                        @if(Helper::userHasPermission('user.users'))
                        <li><a href="{{ route('user.users') }}" @if(isset($set) && $set == 'users') class="active" @endif>Users</a></li>
                        @endif
                        @if(Helper::userHasPermission('user.roles'))
                        <li><a href="{{ route('user.roles') }}" @if(isset($set) && $set == 'roles') class="active" @endif>Roles</a></li>
                        @endif
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
