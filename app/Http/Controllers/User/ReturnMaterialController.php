<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use Validator;
use DataTables;
use App\Models\Project;
use App\Models\MaterialCategory;
use App\Models\Unit;
use App\Models\Stock;
use App\Models\ReturnMaterial;

class ReturnMaterialController extends Controller
{
    public function index()
    {
        $data['set'] = 'return_materials';
        return view('user.return_material.return_materials',$data);
    }

    public function get_return_materials(Request $request)
    {
        if($request->ajax())
        {
            $data = ReturnMaterial::orderby('rmaterial_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('return_date', function($row){

                        $return_date = date('d M Y', strtotime($row->rmaterial_date));

                        return $return_date;
                    })
                    ->addColumn('project_name', function($row){

                        $project_name = $row->project->project_name;

                        return $project_name;
                    })
                    ->addColumn('category_name', function($row){

                        $category_name = $row->material->category->mcategory_name;

                        return $category_name;
                    })
                    ->addColumn('material_name', function($row){

                        $material_name = $row->material->material_name;

                        return $material_name;
                    })
                    ->addColumn('unit_name', function($row){

                        $unit_name = $row->unit->unit_name;

                        return $unit_name;
                    })
                    ->only(config('response-fields.'.request()->route()->getName(), []))
                    ->make(true);
        }
    }

    public function add_return_material(Request $request)
    {
        if ($request->has('submit') && app(\App\Services\WorkflowSchema::class)->ready()) {
            app(\App\Services\MaterialMovementService::class)->save($request, true);
            return redirect()->back()->with('success', 'Materials returned to central stores.');
        }
        if($request->has('submit'))
        {
            $rules = ['rmaterial_date' => 'required',
                      'rmaterial_project' => 'required',
                      'rmaterial_category' => 'required',
                      'rmaterial_material' => 'required',
                      'rmaterial_qty' => 'required'];

            $messages = ['rmaterial_date.required' => 'Please Select Date',
                         'rmaterial_project.required' => 'Please Select Project',
                         'rmaterial_category.required' => 'Please Select Category',
                         'rmaterial_material.required' => 'Please Select Material',
                         'rmaterial_qty.required' => 'Please Enter Quantity'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $stock = Stock::where('stock_material', $request->rmaterial_material)->first();

                $ins['rmaterial_date']       = $request->rmaterial_date;
                $ins['rmaterial_project']    = $request->rmaterial_project;
                $ins['rmaterial_category']   = $request->rmaterial_category;
                $ins['rmaterial_material']   = $request->rmaterial_material;
                $ins['rmaterial_unit']       = $request->rmaterial_unit;
                $ins['rmaterial_qty']        = $request->rmaterial_qty;
                $ins['rmaterial_added_on']   = date('Y-m-d H:i:s');
                $ins['rmaterial_added_by']   = Auth::user()->user_id;
                $ins['rmaterial_updated_on'] = date('Y-m-d H:i:s');
                $ins['rmaterial_updated_by'] = Auth::user()->user_id;
                $ins['rmaterial_status']     = 1;

                $add = ReturnMaterial::create($ins);

                if($add)
                {
                    $upd['stock_qty']        = $stock->stock_qty + $request->rmaterial_qty;
                    $upd['stock_updated_on'] = date('Y-m-d H:i:s');
                    $upd['stock_updated_by'] = Auth::user()->user_id;

                    Stock::where('stock_id', $stock->stock_id)->update($upd);

                    return redirect()->back()->with('success','Material Returned Successfully');
                }
            }
        }

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();
        $data['categories'] = MaterialCategory::where('mcategory_status',1)->orderby('mcategory_name','asc')->get();
        $data['units'] = Unit::where('unit_status',1)->orderby('unit_name','asc')->get();

        $data['set'] = 'return_materials';
        return view('user.return_material.add_return_material',$data);
    }
}
