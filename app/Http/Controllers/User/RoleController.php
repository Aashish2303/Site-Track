<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use Validator;
use DataTables;
use App\Models\MainModule;
use App\Models\Module;
use App\Models\ModulePermission;
use App\Models\Role;
use App\Models\RolePermission;

class RoleController extends Controller
{
    public function index()
    {
        $data['set'] = 'roles';
        return view('user.role.roles',$data);
    }

    public function get_roles(Request $request)
    {
        if($request->ajax())
        {
            $data = Role::orderby('role_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $btn = '';

                        if(Helper::userHasPermission('user.edit_role'))
                        {
                            $btn .= '<a href="'.route('user.edit_role',['id'=>$row->role_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.role_status'))
                        {
                            if($row->role_status == 1)
                            {
                                $btn .= '<a href="'.route('user.role_status',['id'=>$row->role_id,'status'=>$row->role_status]).'" class="btn btn-icon btn-sm btn-danger" title="Click to disable" data-st-click="confirm_msg(event)"><i class="ti ti-ban"></i></a> ';
                            }
                            else
                            {
                                $btn .= '<a href="'.route('user.role_status',['id'=>$row->role_id,'status'=>$row->role_status]).'" class="btn btn-icon btn-sm btn-warning" title="Click to enable" data-st-click="confirm_msg(event)"><i class="ti ti-check"></i></a> ';
                            }
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->only(config('response-fields.'.request()->route()->getName(), []))
                    ->make(true);
        }
    }

    public function add_role(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['role_name' => 'required'];

            $messages = ['role_name.required' => 'Please Enter Role'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_role = Role::where('role_name',$request->role_name)->count();

                if($check_role > 0)
                {
                    return redirect()->back()->with('error','Role Already Added');
                }

                $ins['role_name']       = $request->role_name;
                $ins['role_added_on']   = date('Y-m-d H:i:s');
                $ins['role_added_by']   = Auth::user()->user_id;
                $ins['role_updated_on'] = date('Y-m-d H:i:s');
                $ins['role_updated_by'] = Auth::user()->user_id;
                $ins['role_status']     = 1;

                $add = Role::create($ins);

                if($add)
                {
                    if($request->has('permissions'))
                    {
                        foreach($request->permissions as $rpermission)
                        {
                            $ins_permission['rpermission_role']       = $add->role_id;
                            $ins_permission['rpermission_permission'] = $rpermission;
                            $ins_permission['rpermission_added_on']   = date('Y-m-d H:i:s');
                            $ins_permission['rpermission_added_by']   = Auth::user()->user_id;
                            $ins_permission['rpermission_updated_on'] = date('Y-m-d H:i:s');
                            $ins_permission['rpermission_updated_by'] = Auth::user()->user_id;
                            $ins_permission['rpermission_status']     = 1;

                            RolePermission::create($ins_permission);
                        }
                    }

                    return redirect()->back()->with('success','Role Added Successfully');
                }
            }
        }

        $data['main_modules'] = MainModule::where('mmodule_status',1)->orderby('mmodule_order','asc')->get();

        $all_modules = Module::where('module_status',1)->get();

        foreach($all_modules as $all_module)
        {
            $modules[$all_module->module_main][] = array('id'=>$all_module->module_id,'name'=>$all_module->module_name);
        }

        $data['modules'] = $modules;

        $module_permissions = ModulePermission::where('mpermission_status',1)->get();

        foreach($module_permissions as $module_permission)
        {
            $permissions[$module_permission->mpermission_module][] = array('id'=>$module_permission->mpermission_id,'name'=>$module_permission->mpermission_name);
        }

        $data['permissions'] = $permissions;

        $data['set'] = 'roles';
        return view('user.role.add_role',$data);
    }

    public function edit_role(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['role_name' => 'required'];

            $messages = ['role_name.required' => 'Please Enter Role'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_role = Role::where('role_name',$request->role_name)->where('role_id','!=',$request->route('id'))->count();

                if($check_role > 0)
                {
                    return redirect()->back()->with('error','Role Already Added');
                }

                $upd['role_name']       = $request->role_name;
                $upd['role_updated_on'] = date('Y-m-d H:i:s');
                $upd['role_updated_by'] = Auth::user()->user_id;

                Role::where('role_id',$request->route('id'))->update($upd);

                RolePermission::where('rpermission_role',$request->route('id'))->delete();

                if($request->has('permissions'))
                {
                    foreach($request->permissions as $rpermission)
                    {
                        $ins_permission['rpermission_role']       = $request->route('id');
                        $ins_permission['rpermission_permission'] = $rpermission;
                        $ins_permission['rpermission_added_on']   = date('Y-m-d H:i:s');
                        $ins_permission['rpermission_added_by']   = Auth::user()->user_id;
                        $ins_permission['rpermission_updated_on'] = date('Y-m-d H:i:s');
                        $ins_permission['rpermission_updated_by'] = Auth::user()->user_id;
                        $ins_permission['rpermission_status']     = 1;

                        RolePermission::create($ins_permission);
                    }
                }

                return redirect()->back()->with('success','Role Updated Successfully');
            }
        }

        $data['role'] = Role::where('role_id',$request->route('id'))->first();

        if(!isset($data['role']))
        {
            return redirect()->route('user.roles');
        }

        $data['main_modules'] = MainModule::where('mmodule_status',1)->orderby('mmodule_order','asc')->get();

        $all_modules = Module::where('module_status',1)->get();

        foreach($all_modules as $all_module)
        {
            $modules[$all_module->module_main][] = array('id'=>$all_module->module_id,'name'=>$all_module->module_name);
        }

        $data['modules'] = $modules;

        $module_permissions = ModulePermission::where('mpermission_status',1)->get();

        foreach($module_permissions as $module_permission)
        {
            $permissions[$module_permission->mpermission_module][] = array('id'=>$module_permission->mpermission_id,'name'=>$module_permission->mpermission_name);
        }

        $data['permissions'] = $permissions;

        $where_permission['rpermission_role'] = $request->route('id');
        $get_rpermissions = RolePermission::where($where_permission)->get();

        if($get_rpermissions->count() > 0)
        {
            $role_permissions = array_column($get_rpermissions->toArray(),'rpermission_permission');

            $data['role_permissions'] = $role_permissions;
        }

        $data['set'] = 'roles';
        return view('user.role.edit_role',$data);
    }

    public function role_status(Request $request)
    {
        $id = $request->route('id');
        $status = $request->route('status');

        if($status == 1)
        {
            $upd['role_status'] = 0;
        }
        else
        {
            $upd['role_status'] = 1;
        }

        $where['role_id'] = $id;

        $update = Role::where($where)->update($upd);

        if($update)
        {
            return redirect()->back()->with('success','Status Changed Successfully');
        }
    }
}
