<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use Validator;
use DataTables;
use App\Models\MaterialCategory;
use App\Models\Material;
use App\Models\Unit;

class MaterialController extends Controller
{
    public function index()
    {
        $data['set'] = 'materials';
        return view('user.material.materials',$data);
    }

    public function get_materials(Request $request)
    {
        if($request->ajax())
        {
            $data = Material::orderby('material_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('category_name', function($row){

                        $category_name = $row->category->mcategory_name;

                        return $category_name;
                    })
                    ->addColumn('unit_name', function($row){

                        $unit_name = $row->unit->unit_name;

                        return $unit_name;
                    })
                    ->addColumn('action', function($row){

                        $btn = '';

                        if(Helper::userHasPermission('user.edit_material'))
                        {
                            $btn .= '<a href="'.route('user.edit_material',['id'=>$row->material_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.material_status'))
                        {
                            if($row->material_status == 1)
                            {
                                $btn .= '<a href="'.route('user.material_status',['id'=>$row->material_id,'status'=>$row->material_status]).'" class="btn btn-icon btn-sm btn-danger" title="Click to disable" data-st-click="confirm_msg(event)"><i class="ti ti-ban"></i></a> ';
                            }
                            else
                            {
                                $btn .= '<a href="'.route('user.material_status',['id'=>$row->material_id,'status'=>$row->material_status]).'" class="btn btn-icon btn-sm btn-warning" title="Click to enable" data-st-click="confirm_msg(event)"><i class="ti ti-check"></i></a> ';
                            }
                        }

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->only(config('response-fields.'.request()->route()->getName(), []))
                    ->make(true);
        }
    }

    public function add_material(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['material_category' => 'required',
                      'material_name' => 'required',
                      'material_unit' => 'required'];

            $messages = ['material_category.required' => 'Please Select Category',
                         'material_name.required' => 'Please Enter Material',
                         'material_unit.required' => 'Please Select Unit'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_user = Material::where('material_name',$request->material_name)->count();

                if($check_user > 0)
                {
                    return redirect()->back()->with('error','Material Already Added');
                }

                $ins['material_category']   = $request->material_category;
                $ins['material_name']       = $request->material_name;
                $ins['material_unit']       = $request->material_unit;
                $ins['material_hsn']        = $request->material_hsn;
                $ins['material_added_on']   = date('Y-m-d H:i:s');
                $ins['material_added_by']   = Auth::user()->user_id;
                $ins['material_updated_on'] = date('Y-m-d H:i:s');
                $ins['material_updated_by'] = Auth::user()->user_id;
                $ins['material_status']     = 1;

                $add = Material::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','Material Added Successfully');
                }
            }
        }

        $data['categories'] = MaterialCategory::where('mcategory_status',1)->orderby('mcategory_name','asc')->get();
        $data['units'] = Unit::where('unit_status',1)->orderby('unit_name','asc')->get();

        $data['set'] = 'materials';
        return view('user.material.add_material',$data);
    }

    public function edit_material(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['material_category' => 'required',
                      'material_name' => 'required',
                      'material_unit' => 'required'];

            $messages = ['material_category.required' => 'Please Select Category',
                         'material_name.required' => 'Please Enter Material',
                         'material_unit.required' => 'Please Select Unit'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $check_material = Material::where('material_name',$request->material_name)->where('material_id','!=',$request->route('id'))->count();

                if($check_material > 0)
                {
                    return redirect()->back()->with('error','Material Already Added');
                }

                $upd['material_category']   = $request->material_category;
                $upd['material_name']       = $request->material_name;
                $upd['material_unit']       = $request->material_unit;
                $upd['material_hsn']        = $request->material_hsn;
                $upd['material_updated_on'] = date('Y-m-d H:i:s');
                $upd['material_updated_by'] = Auth::user()->user_id;

                Material::where('material_id',$request->route('id'))->update($upd);

                return redirect()->back()->with('success','Material Updated Successfully');
            }
        }

        $data['material'] = Material::where('material_id',$request->route('id'))->first();

        if(!isset($data['material']))
        {
            return redirect()->route('user.materials');
        }

        $data['categories'] = MaterialCategory::where('mcategory_status',1)->orderby('mcategory_name','asc')->get();
        $data['units'] = Unit::where('unit_status',1)->orderby('unit_name','asc')->get();

        $data['set'] = 'materials';
        return view('user.material.edit_material',$data);
    }

    public function material_status(Request $request)
    {
        $id = $request->route('id');
        $status = $request->route('status');

        if($status == 1)
        {
            $upd['material_status'] = 0;
        }
        else
        {
            $upd['material_status'] = 1;
        }

        $where['material_id'] = $id;

        $update = Material::where($where)->update($upd);

        if($update)
        {
            return redirect()->back()->with('success','Status Changed Successfully');
        }
    }
}
