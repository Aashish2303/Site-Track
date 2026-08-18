<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use Validator;
use DataTables;
use App\Models\MaterialCategory;

class MaterialCategoryController extends Controller
{
    public function index()
    {
        $data['set'] = 'material_category';
        return view('user.material_category.material_category', $data);
    }

    public function get_material_category(Request $request)
    {
        if($request->ajax()) 
        {
            $data = MaterialCategory::orderby('mcategory_id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    
                    $btn = '';

                    if(Helper::userHasPermission('user.edit_material_category')) 
                    {
                        $btn .= '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-success" onclick="edit_material_category(' . $row->mcategory_id . ')" title="Edit"><i class="ti ti-edit"></i></a> ';
                    }

                    if(Helper::userHasPermission('user.material_category_status')) 
                    {
                        if($row->mcategory_status == 1) 
                        {
                            $btn .= '<a href="' . route('user.material_category_status', ['id' => $row->mcategory_id, 'status' => $row->mcategory_status]) . '" class="btn btn-icon btn-sm btn-danger" title="Click to disable" onclick="confirm_msg(event)"><i class="ti ti-ban"></i></a> ';
                        } 
                        else 
                        {
                            $btn .= '<a href="' . route('user.material_category_status', ['id' => $row->mcategory_id, 'status' => $row->mcategory_status]) . '" class="btn btn-icon btn-sm btn-warning" title="Click to enable" onclick="confirm_msg(event)"><i class="ti ti-check"></i></a> ';
                        }
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function add_material_category(Request $request)
    {
        if($request->has('submit')) 
        {
            $rules = ['mcategory_name' => 'required'];

            $messages = ['mcategory_name.required' => 'Please Enter Category'];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            } 
            else 
            {
                $where_check['mcategory_name'] = $request->mcategory_name;
                $check_category = MaterialCategory::where($where_check)->count();

                if($check_category > 0)
                {
                    return redirect()->back()->with('error', 'Category Already Added')->withInput();
                }

                $ins['mcategory_name']       = $request->mcategory_name;
                $ins['mcategory_added_on']   = date('Y-m-d H:i:s');
                $ins['mcategory_added_by']   = Auth::user()->user_id;
                $ins['mcategory_updated_on'] = date('Y-m-d H:i:s');
                $ins['mcategory_updated_by'] = Auth::user()->user_id;
                $ins['mcategory_status']     = 1;

                $add = MaterialCategory::create($ins);

                if($add) 
                {
                    return redirect()->back()->with('success', 'Category Added Successfully');
                }
            }
        }
    }

    public function edit_material_category(Request $request)
    {
        if($request->has('submit')) 
        {
            $rules = ['mcategory_name' => 'required'];

            $messages = ['mcategory_name.required' => 'Please Enter Category'];

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else 
            {
                $where_check['mcategory_name'] = $request->mcategory_name;
                $check_category = MaterialCategory::where($where_check)->where('mcategory_id', '!=', $request->mcategory_id)->count();

                if($check_category > 0) 
                {
                    return redirect()->back()->with('error', 'Category Already Added')->withInput();
                }

                $upd['mcategory_name']       = $request->mcategory_name;
                $upd['mcategory_updated_on'] = date('Y-m-d H:i:s');
                $upd['mcategory_updated_by'] = Auth::user()->user_id;

                MaterialCategory::where('mcategory_id', $request->mcategory_id)->update($upd);

                return redirect()->back()->with('success', 'Category Updated Successfully');
            }
        }
    }

    public function get_material_category_details(Request $request)
    {
        $data['category'] = MaterialCategory::where('mcategory_id', $request->mcategory_id)->first();

        $data['set'] = 'material_category';
        return view('user.material_category.edit_material_category', $data);
    }

    public function material_category_status(Request $request)
    {
        $id = $request->segment(3);
        $status = $request->segment(4);

        if($status == 1)
        {
            $upd['mcategory_status'] = 0;
        }
        else
        {
            $upd['mcategory_status'] = 1;
        }

        $where['mcategory_id'] = $id;

        $update = MaterialCategory::where($where)->update($upd);

        if($update)
        {
            return redirect()->back()->with('success', 'Status Changed Successfully');
        }
    }
}
