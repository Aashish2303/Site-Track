@php
    $navSet = $set ?? '';
    $globalLinks = [
        ['user.dashboard', 'ti-layout-board', 'Dashboard', 'dashboard'],
        ['user.projects', 'ti-building', 'Projects Directory', 'projects'],
        ['user.subcontractor_ledger', 'ti-notebook', 'Subcon Ledger', 'subcontractor_ledger'],
        ['user.vendor_ledger', 'ti-receipt', 'Vendor Ledger', 'vendor_ledger'],
        ['user.reports', 'ti-report-analytics', 'Reports', 'reports'],
        ['user.vendors', 'ti-briefcase', 'Vendor Directory', 'vendors'],
    ];
    $siteLinks = [
        ['user.project_workspace', 'ti-building', 'Project overview', 'project_workspace'],
        ['user.daily_works', 'ti-calendar', 'DWR · Daily work', 'daily_works'],
        ['user.daily_progress', 'ti-chart-bar', 'DPR · Daily progress', 'daily_progress'],
        ['user.boq_tracker', 'ti-list-check', 'BOQ tracker', 'boq_tracker'],
        ['user.site_materials', 'ti-package', 'Site materials', 'site_materials'],
        ['user.assign_materials', 'ti-truck-delivery', 'Receive from stores', 'assign_materials'],
        ['user.return_materials', 'ti-arrow-back', 'Return to stores', 'return_materials'],
        ['user.indents', 'ti-clipboard', 'Material requests', 'indents'],
        ['user.labour_trackers', 'ti-users-group', 'Labour attendance', 'labour_trackers'],
        ['user.labour_payments', 'ti-wallet', 'Labour payments', 'labour_payments'],
        ['user.issues', 'ti-alert-triangle', 'Site issues', 'issues'],
    ];
    $administration = [
        ['user.clients', 'Clients', 'clients'], ['user.subcontractors', 'Subcontractors', 'subcontractors'],
        ['user.materials', 'Material catalogue', 'materials'], ['user.orders', 'Central purchase orders', 'orders'],
        ['user.stocks', 'Central stores', 'stocks'], ['user.material_category', 'Material categories', 'material_category'],
        ['user.category', 'Work categories', 'category'], ['user.units', 'Units', 'units'],
        ['user.issue_category', 'Issue categories', 'issue_category'], ['user.qms', 'QMS reports', 'qms'],
        ['user.users', 'Users', 'users'], ['user.roles', 'Roles & permissions', 'roles'],
        ['user.settings', 'Settings', 'settings'], ['user.email_config_settings', 'Email settings', 'email_config_settings'],
    ];
    $canNavigate = function ($route) {
        return match ($route) {
            'user.reports', 'user.labour_payments' => \App\Services\WorkflowAccess::can($route),
            'user.site_materials' => Helper::userHasPermission('user.assign_materials'),
            'user.project_workspace' => Helper::userHasPermission('user.view_project'),
            default => Helper::userHasPermission($route),
        };
    };
@endphp
<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route(($inProjectWorkspace ?? false) ? 'user.project_workspace' : 'user.projects') }}" class="st-brand">@include('user.partials.brand')</a>
        <button type="button" class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn" aria-label="Collapse navigation"><i class="ti ti-arrow-bar-to-left"></i></button>
        <button type="button" class="sidebar-close" aria-label="Close navigation"><i class="ti ti-x align-middle"></i></button>
    </div>
    <div class="sidebar-inner" data-simplebar><div id="sidebar-menu" class="sidebar-menu"><ul role="menu" aria-label="{{ ($inProjectWorkspace ?? false) ? 'Project navigation menu' : 'Global navigation menu' }}">
        @if(!($inProjectWorkspace ?? false))
        <li class="menu-title"><span>GLOBAL OVERVIEW</span></li>
        @foreach($globalLinks as [$route, $icon, $label, $key])
        @if($canNavigate($route))<li><a href="{{ route($route) }}" @class(['active' => $navSet === $key])><i class="ti {{ $icon }}" aria-hidden="true"></i><span>{{ $label }}</span></a></li>@endif
        @endforeach
        <li class="submenu mt-3">
            <a href="javascript:void(0)" @class(['active subdrop' => in_array($navSet, array_column($administration, 2))])><i class="ti ti-settings" aria-hidden="true"></i><span>Administration</span><span class="menu-arrow"></span></a>
            <ul @if(in_array($navSet, array_column($administration, 2))) style="display:block" @endif>
                @foreach($administration as [$route, $label, $key])
                @if(Helper::userHasPermission($route))<li><a href="{{ route($route) }}" @class(['active' => $navSet === $key])>{{ $label }}</a></li>@endif
                @endforeach
            </ul>
        </li>
        @else
        <li class="st-back-global"><a href="{{ route('user.projects') }}"><i class="ti ti-arrow-left" aria-hidden="true"></i><span>Back to global overview</span></a></li>
        <li class="menu-title st-workspace-title"><span>{{ $workspaceProject->project_name }}</span></li>
        @foreach($siteLinks as [$route, $icon, $label, $key])
        @if($canNavigate($route))<li><a href="{{ route($route) }}" @class(['active' => $navSet === $key])><i class="ti {{ $icon }}" aria-hidden="true"></i><span>{{ $label }}</span></a></li>@endif
        @endforeach
        @endif
    </ul></div></div>
    <div class="st-sidebar-account"><a href="{{ route('user.profile') }}"><span class="st-account-initials">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(Auth::user()->user_name, 0, 2)) }}</span><span class="st-account-copy"><strong>{{ Auth::user()->user_name }}</strong><small>{{ Request()->role?->role_name }}</small></span></a><a href="{{ route('user.logout') }}" aria-label="Sign out"><i class="ti ti-logout" aria-hidden="true"></i></a></div>
</div>
