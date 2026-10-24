<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Rules\AccountPassword;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ValidateUserInput
{
    public function handle(Request $request, Closure $next)
    {
        $action = substr((string) $request->route()->getName(), 5);
        $parameters = $request->route()->parameters();
        Validator::make($parameters, ['id' => 'sometimes|integer|min:1', 'status' => 'sometimes|integer|in:0,1,2,3'])->validate();

        if ($action === 'status_quotation') {
            Validator::make($parameters, ['status' => 'required|integer|in:2,3'])->validate();
        }

        if ($request->isMethod('post')) {
            $form = preg_replace('/^(add|edit)_/', '', $action);
            $rules = config('input-validation.'.$form, []);
            if ($action === 'profile') {
                $rules = config('input-validation.profile');
            }
            // Dashboard filter endpoints are not mutation forms.
            if (! str_starts_with($action, 'add_') && ! str_starts_with($action, 'edit_') && ! in_array($action, ['profile', 'settings', 'email_config_settings'], true)) {
                $rules = [];
            }
            $rules['user_vpassword'] = 'prohibited';
            foreach (['user_image', 'issue_image', 'settings_logo', 'settings_wlogo', 'settings_favicon'] as $image) {
                $rules[$image] = 'nullable|file|image|mimes:jpg,jpeg,png,webp,ico|max:2048';
            }
            $rules['vendor_card'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240';
            $rules['iquote_quotation'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240';
            $rules['boq_file'] = ($action === 'add_project' ? 'required' : 'nullable').'|file|mimes:xls,xlsx|max:10240';
            if (in_array($action, ['add_user', 'edit_user'], true)) {
                $id = $action === 'edit_user' ? $request->route('id') : null;
                $rules['user_lname'] = array_merge(explode('|', $rules['user_lname']), [Rule::unique('users', 'user_lname')->ignore($id, 'user_id')]);
                $rules['user_email'] = array_merge(explode('|', $rules['user_email']), [Rule::unique('users', 'user_email')->ignore($id, 'user_id')]);
                $unchanged = $id && is_string($request->user_password) && Hash::check($request->user_password, User::whereKey($id)->firstOrFail()->getAuthPassword());
                $rules['user_password'] = [$id ? 'nullable' : 'required', 'string', $unchanged ? 'max:72' : new AccountPassword];
            }
            if ($action === 'change_password') {
                $rules += ['current_password' => 'required|string|max:1024', 'new_password' => ['required', 'string', new AccountPassword], 'confirm_password' => 'required|string|same:new_password'];
            }
            $statusRules = [
                'add_project_status' => ['project_id', 'projects', 'project_id', 3],
                'add_daily_work_status' => ['dwork_id', 'daily_works', 'dwork_id', 5],
                'add_daily_progress_status' => ['dprogress_id', 'daily_progress', 'dprogress_id', 5],
                'add_order_pay_status' => ['order_id', 'orders', 'order_id', 5],
                'add_issue_status' => ['issue_id', 'issues', 'issue_id', 4],
                'add_indent_item_status' => ['item_id', 'indent_items', 'iitem_id', 4],
            ];
            if (isset($statusRules[$action])) {
                [$field, $table, $column, $max] = $statusRules[$action];
                $rules[$field] = "required|integer|exists:{$table},{$column}";
                $rules['status'] = ($action === 'add_order_pay_status' ? 'required|integer|in:1,2,5' : 'required|integer|between:1,'.$max);
            }
            if ($action === 'add_boq_item') {
                $rules['project_id'] = 'required|integer|exists:projects,project_id';
            }
            if ($action === 'edit_boq_item') {
                $rules['pboq_id'] = 'required|integer|exists:project_boqs,pboq_id';
            }
            $lookups = [
                'get_category_details' => ['category_id', 'category', 'category_id'],
                'edit_category' => ['category_id', 'category', 'category_id'],
                'get_material_category_details' => ['mcategory_id', 'material_category', 'mcategory_id'],
                'edit_material_category' => ['mcategory_id', 'material_category', 'mcategory_id'],
                'get_issue_category_details' => ['icategory_id', 'issue_category', 'icategory_id'],
                'edit_issue_category' => ['icategory_id', 'issue_category', 'icategory_id'],
                'get_unit_details' => ['unit_id', 'units', 'unit_id'],
                'edit_unit' => ['unit_id', 'units', 'unit_id'],
                'get_boq_project_details' => ['project_id', 'projects', 'project_id'],
                'get_boq_item_details' => ['pboq_id', 'project_boqs', 'pboq_id'],
                'select_boq' => ['project_id', 'projects', 'project_id'],
                'select_materials' => ['category_id', 'material_category', 'mcategory_id'],
                'select_material_stock' => ['material_id', 'materials', 'material_id'],
                'select_material_unit' => ['material_id', 'materials', 'material_id'],
                'project_status' => ['project_id', 'projects', 'project_id'],
                'daily_work_status' => ['dwork_id', 'daily_works', 'dwork_id'],
                'daily_progress_status' => ['dprogress_id', 'daily_progress', 'dprogress_id'],
                'issue_status' => ['issue_id', 'issues', 'issue_id'],
                'order_pay_status' => ['order_id', 'orders', 'order_id'],
                'indent_item_status' => ['item_id', 'indent_items', 'iitem_id'],
            ];
            if (isset($lookups[$action])) {
                [$field, $table, $column] = $lookups[$action];
                $rules[$field] = "required|integer|exists:{$table},{$column}";
            }
            $request->validate($rules);
            if (in_array($action, ['add_indent', 'edit_indent'], true)) {
                $keys = array_keys($request->input('materials.NAME'));
                foreach (['CATEGORY', 'SIZE', 'QTY', 'UNIT'] as $column) {
                    if ($keys !== array_keys($request->input('materials.'.$column))) {
                        throw ValidationException::withMessages(['materials' => 'Each material row must have matching fields.']);
                    }
                }
            }
            if ($form !== $action || in_array($action, ['profile', 'change_password', 'settings', 'email_config_settings'], true)) {
                // Do not let omission of a client-side submit button bypass controller handling.
                $request->merge(['submit' => 'submit']);
            }
        }

        return $next($request);
    }
}
