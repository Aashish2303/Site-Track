<?php

namespace App\Services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

/** Opaque, resource-bound record references; authorisation remains mandatory. */
class PublicReference
{
    public function encode(string $resource, int|string $id): string
    {
        $key = $resource.':'.$id;
        $refs = session('public_references', []);
        if (! isset($refs[$key])) {
            if (count($refs) > 3000) {
                $refs = [];
            }
            $refs[$key] = 'pr_'.rtrim(strtr(Crypt::encryptString($key), '+/', '-_'), '=');
            session(['public_references' => $refs]);
        }

        return $refs[$key];
    }

    public function decode(string $token, string|array $resources): int
    {
        try {
            $plain = Crypt::decryptString(strtr(substr($token, 3), '-_', '+/'));
            [$resource, $id] = explode(':', $plain, 2);
            abort_unless(in_array($resource, (array) $resources, true) && ctype_digit($id) && (int) $id > 0, 404);

            return (int) $id;
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    public function field(string $field): ?string
    {
        $map = [
            'project' => 'projects', 'project_id' => 'projects', 'pboq_project' => 'projects',
            'dwork_project' => 'projects', 'dprogress_project' => 'projects', 'amaterial_project' => 'projects',
            'rmaterial_project' => 'projects', 'indent_project' => 'projects', 'ltracker_project' => 'projects',
            'issue_project' => 'projects', 'sledger_project' => 'projects', 'vledger_project' => 'projects',
            'user_role' => 'roles', 'role_id' => 'roles',
            'project_client' => 'clients', 'project_engineer' => 'users', 'dwork_incharge' => 'users',
            'dprogress_incharge' => 'users', 'ltracker_incharge' => 'users', 'issue_assign' => 'users', 'indent_engineer' => 'users',
            'dwork_category' => 'category', 'dprogress_category' => 'category', 'trade' => 'category',
            'issue_category' => 'issue_category', 'icategory_id' => 'issue_category',
            'mcategory_id' => 'material_category', 'material_category' => 'material_category', 'vendor_category' => 'material_category',
            'amaterial_category' => 'material_category', 'rmaterial_category' => 'material_category',
            'category_id' => 'category', 'dprogress_boq' => 'project_boqs', 'pboq_id' => 'project_boqs',
            'dwork_id' => 'daily_works', 'dprogress_id' => 'daily_progress', 'issue_id' => 'issues',
            'order_id' => 'orders', 'item_id' => 'indent_items', 'unit_id' => 'units', 'material_unit' => 'units', 'order_unit' => 'units',
            'material' => 'materials', 'material_id' => 'materials', 'amaterial_material' => 'materials',
            'rmaterial_material' => 'materials', 'order_material' => 'materials',
            'subcontractor_id' => 'subcontractors', 'dwork_subcontractor' => 'subcontractors',
            'dprogress_subcontractor' => 'subcontractors', 'ltracker_subcontractor' => 'subcontractors', 'sledger_subcontractor' => 'subcontractors',
            'order_vendor' => 'vendors', 'iquote_vendor' => 'vendors', 'vledger_vendor' => 'vendors',
        ];
        if (preg_match('/^permissions(?:\[|\.|$)/', $field)) {
            return 'module_permissions';
        }
        if (preg_match('/^workers(?:\[|\.)CATEGORY/', $field)) {
            return 'category';
        }
        if (preg_match('/^materials(?:\[|\.)(NAME|CATEGORY|UNIT)/', $field, $m)) {
            return ['NAME' => 'materials', 'CATEGORY' => 'category', 'UNIT' => 'units'][$m[1]];
        }
        if ($field === 'category' && request()->routeIs('user.vendors*', 'user.get_vendors')) {
            return 'material_category';
        }
        if ($field === 'category_id' && request()->routeIs('user.select_materials')) {
            return 'material_category';
        }

        return $map[$field] ?? null;
    }

    public function controllerResource(string $controller): ?string
    {
        return [
            'UserController' => 'users', 'RoleController' => 'roles', 'ProjectController' => 'projects',
            'ClientController' => 'clients', 'DailyWorkController' => 'daily_works', 'DailyProgressController' => 'daily_progress',
            'ProgressReviewController' => 'daily_progress', 'OrderController' => 'orders', 'MaterialController' => 'materials',
            'MaterialCategoryController' => 'material_category', 'IndentController' => 'indents', 'LabourTrackerController' => 'labour_trackers',
            'IssueController' => 'issues', 'IssueCategoryController' => 'issue_category', 'VendorController' => 'vendors',
            'SubcontractorController' => 'subcontractors', 'CategoryController' => 'category', 'UnitController' => 'units',
        ][$controller] ?? null;
    }

    public function html(string $html): string
    {
        $html = preg_replace_callback('~(/user/)([a-z_]+)/(\d+)~', function ($m) {
            $prefix = $m[2];
            $entity = preg_replace('/^(edit_|view_|reject_|status_)/', '', preg_replace('/_status$/', '', $prefix));
            $resource = ['user' => 'users', 'role' => 'roles', 'project' => 'projects', 'client' => 'clients', 'daily_work' => 'daily_works',
                'daily_progress' => 'daily_progress', 'order' => 'orders', 'order_delivery' => 'orders', 'material' => 'materials',
                'material_category' => 'material_category', 'indent' => 'indents', 'indent_items' => 'indents', 'indent_quotations' => 'indents',
                'download_indent' => 'indents', 'quotations' => 'indents', 'quotation' => 'indent_quotations', 'labour_tracker' => 'labour_trackers',
                'issue' => 'issues', 'issue_category' => 'issue_category', 'vendor' => 'vendors', 'subcontractor' => 'subcontractors', 'category' => 'category', 'unit' => 'units'][$entity] ?? null;

            return $resource ? $m[1].$prefix.'/'.$this->encode($resource, $m[3]) : $m[0];
        }, $html);
        $html = preg_replace_callback('~/user/progress/(\d+)(/photos/(\d+)|/review)~', fn ($m) => '/user/progress/'.$this->encode('daily_progress', $m[1]).(isset($m[3]) ? '/photos/'.$this->encode('progress_photos', $m[3]) : '/review'), $html);
        $html = preg_replace_callback('~([?&](?:amp;)?)(project|material|trade)=(\d+)~', fn ($m) => $m[1].$m[2].'='.$this->encode($this->field($m[2]), $m[3]), $html);
        $partial = ['user.select_boq' => 'project_boqs', 'user.select_materials' => 'materials'][request()->route()?->getName() ?? ''] ?? null;
        if ($partial) {
            $html = preg_replace_callback('~\bvalue=(["\x27])(\d+)\1~', fn ($v) => 'value='.$v[1].$this->encode($partial, $v[2]).$v[1], $html);
        }
        $html = preg_replace_callback('~<select\b([^>]*)>(.*?)</select>~s', function ($m) {
            if (! preg_match('~\bname=["\x27]([^"\x27]+)~', $m[1], $name) || ! $resource = $this->field($name[1])) {
                return $m[0];
            }

            return '<select'.$m[1].'>'.preg_replace_callback('~\bvalue=(["\x27])(\d+)\1~', fn ($v) => 'value='.$v[1].$this->encode($resource, $v[2]).$v[1], $m[2]).'</select>';
        }, $html);
        $html = preg_replace_callback('~<input\b[^>]*>~', function ($m) {
            if (! preg_match('~\bname=["\x27]([^"\x27]+)~', $m[0], $name) || ! $resource = $this->field($name[1])) {
                return $m[0];
            }

            return preg_replace_callback('~\bvalue=(["\x27])(\d+)\1~', fn ($v) => 'value='.$v[1].$this->encode($resource, $v[2]).$v[1], $m[0]);
        }, $html);
        $callbacks = ['project_status' => 'projects', 'add_boq_item' => 'projects', 'edit_boq_item' => 'project_boqs', 'daily_work_status' => 'daily_works',
            'daily_progress_status' => 'daily_progress', 'order_pay_status' => 'orders', 'indent_item_status' => 'indent_items', 'issue_status' => 'issues',
            'edit_category' => 'category', 'edit_material_category' => 'material_category', 'edit_issue_category' => 'issue_category', 'edit_unit' => 'units'];
        $html = preg_replace_callback('~data-st-click="([a-z_]+)\((\d+)\)"~', fn ($m) => isset($callbacks[$m[1]]) ? 'data-st-click="'.$m[1].'(\x27'.$this->encode($callbacks[$m[1]], $m[2]).'\x27)"' : $m[0], $html);
        $html = preg_replace_callback('~data-project="(\d+)"~', fn ($m) => 'data-project="'.$this->encode('projects', $m[1]).'"', $html);

        return str_replace('\\x27', "'", $html);
    }
}
