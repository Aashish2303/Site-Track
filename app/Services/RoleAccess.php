<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/** One permission policy for pages, AJAX endpoints, writes, exports and navigation. */
class RoleAccess
{
    private const PERSONAL = ['user.dashboard', 'user.profile', 'user.change_password', 'user.check_current_password', 'user.logout', 'user.leave_project'];

    private const ALIASES = [
        'user.select_project' => ['user.projects'],
        'user.project_workspace' => ['user.view_project'],
        'user.get_projects' => ['user.projects'],
        'user.get_clients' => ['user.clients'],
        'user.get_daily_works' => ['user.daily_works'],
        'user.get_daily_progress' => ['user.daily_progress'],
        'user.get_assign_materials' => ['user.assign_materials'],
        'user.get_return_materials' => ['user.return_materials'],
        'user.get_orders' => ['user.orders'],
        'user.get_stocks' => ['user.stocks'],
        'user.get_materials' => ['user.materials'],
        'user.get_material_category' => ['user.material_category'],
        'user.get_indents' => ['user.indents'],
        'user.get_labour_trackers' => ['user.labour_trackers'],
        'user.get_issues' => ['user.issues'],
        'user.get_issue_category' => ['user.issue_category'],
        'user.get_subcontractor_ledger' => ['user.subcontractor_ledger'],
        'user.get_vendor_ledger' => ['user.vendor_ledger'],
        'user.get_vendors' => ['user.vendors'],
        'user.get_subcontractors' => ['user.subcontractors'],
        'user.get_category' => ['user.category'],
        'user.get_units' => ['user.units'],
        'user.get_users' => ['user.users'],
        'user.get_roles' => ['user.roles'],
        // The legacy role editor has a single manage-roles capability.
        'user.add_role' => ['user.edit_role'],
        'user.add_project_status' => ['user.project_status'],
        'user.add_daily_work_status' => ['user.daily_work_status'],
        'user.add_daily_progress_status' => ['user.daily_progress_status'],
        'user.add_order_pay_status' => ['user.order_pay_status'],
        'user.add_indent_item_status' => ['user.indent_item_status'],
        'user.add_issue_status' => ['user.issue_status'],
        'user.get_category_details' => ['user.edit_category'],
        'user.get_material_category_details' => ['user.edit_material_category'],
        'user.get_issue_category_details' => ['user.edit_issue_category'],
        'user.get_unit_details' => ['user.edit_unit'],
        'user.get_boq_project_details' => ['user.add_boq_item', 'user.edit_boq_item'],
        'user.get_boq_item_details' => ['user.edit_boq_item'],
        'user.select_boq' => ['user.add_daily_progress', 'user.edit_daily_progress'],
        'user.select_materials' => ['user.add_assign_material', 'user.add_return_material'],
        'user.select_material_stock' => ['user.add_assign_material', 'user.add_return_material'],
        'user.select_material_unit' => ['user.add_order', 'user.edit_order'],
        'user.site_materials' => ['user.assign_materials'],
        'user.record_material_usage' => ['user.site_material_usage'],
        'user.progress_review' => ['user.daily_progress'],
        'user.progress_photo' => ['user.daily_progress'],
    ];

    private ?array $permissions = null;

    private ?object $request = null;

    private ?string $identity = null;

    public function allows(string $route): bool
    {
        $user = Auth::user();
        if (! $user || (int) $user->user_status !== 1) {
            return false;
        }
        $identity = $user->user_id.':'.$user->user_role;
        if ($this->permissions === null || $this->request !== request() || $this->identity !== $identity) {
            $this->request = request();
            $this->identity = $identity;
            $this->permissions = DB::table('role_permissions')
                ->join('roles', 'role_id', '=', 'rpermission_role')
                ->join('module_permissions', 'mpermission_id', '=', 'rpermission_permission')
                ->where('role_id', $user->user_role)->where('role_status', 1)
                ->where('rpermission_status', 1)->where('mpermission_status', 1)
                ->pluck('mpermission_slug')->all();
        }
        if (in_array($route, self::PERSONAL, true)) {
            return true;
        }

        // A route without an explicit mapping or installed permission is denied.
        return (bool) array_intersect(self::ALIASES[$route] ?? [$route], $this->permissions);
    }
}
