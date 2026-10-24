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
use App\Models\Material;
use App\Models\Unit;
use App\Models\Stock;
use App\Models\AssignMaterial;

class AssignMaterialController extends Controller
{
    public function index()
    {
        $data['set'] = 'assign_materials';
        return view('user.assign_material.assign_materials',$data);
    }

    public function get_assign_materials(Request $request)
    {
        if($request->ajax())
        {
            $data = AssignMaterial::orderby('amaterial_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('assign_date', function($row){

                        $assign_date = date('d M Y', strtotime($row->amaterial_date));

                        return $assign_date;
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
                    ->make(true);
        }
    }

    public function add_assign_material(Request $request)
    {
        if ($request->has('submit') && app(\App\Services\WorkflowSchema::class)->ready()) {
            app(\App\Services\MaterialMovementService::class)->save($request, false);
            return redirect()->back()->with('success', 'Materials received into the selected site.');
        }
        if($request->has('submit'))
        {
            $rules = ['amaterial_date' => 'required',
                      'amaterial_project' => 'required',
                      'amaterial_category' => 'required',
                      'amaterial_material' => 'required',
                      'amaterial_qty' => 'required'];

            $messages = ['amaterial_date.required' => 'Please Select Date',
                         'amaterial_project.required' => 'Please Select Project',
                         'amaterial_category.required' => 'Please Select Category',
                         'amaterial_material.required' => 'Please Select Material',
                         'amaterial_qty.required' => 'Please Enter Quantity'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $stock = Stock::where('stock_material', $request->amaterial_material)->first();

                if(!$stock || (float) $stock->stock_qty < (float) $request->amaterial_qty)
                {
                    return redirect()->back()->with('error','Assigned quantity cannot exceed available quantity')->withInput();
                }

                $ins['amaterial_date']       = $request->amaterial_date;
                $ins['amaterial_project']    = $request->amaterial_project;
                $ins['amaterial_category']   = $request->amaterial_category;
                $ins['amaterial_material']   = $request->amaterial_material;
                $ins['amaterial_unit']       = $request->amaterial_unit;
                $ins['amaterial_qty']        = $request->amaterial_qty;
                $ins['amaterial_added_on']   = date('Y-m-d H:i:s');
                $ins['amaterial_added_by']   = Auth::user()->user_id;
                $ins['amaterial_updated_on'] = date('Y-m-d H:i:s');
                $ins['amaterial_updated_by'] = Auth::user()->user_id;
                $ins['amaterial_status']     = 1;

                $add = AssignMaterial::create($ins);

                if($add)
                {
                    $upd['stock_qty']        = $stock->stock_qty - $request->amaterial_qty;
                    $upd['stock_updated_on'] = date('Y-m-d H:i:s');
                    $upd['stock_updated_by'] = Auth::user()->user_id;

                    Stock::where('stock_id', $stock->stock_id)->update($upd);

                    return redirect()->back()->with('success','Material Assigned Successfully');
                }
            }
        }

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();
        $data['categories'] = MaterialCategory::where('mcategory_status',1)->orderby('mcategory_name','asc')->get();
        $data['units'] = Unit::where('unit_status',1)->orderby('unit_name','asc')->get();

        $data['set'] = 'assign_materials';
        return view('user.assign_material.add_assign_material',$data);
    }

    public function select_materials(Request $request)
    {
        $data['materials'] = Material::where('material_category', $request->category_id)->where('material_status',1)->orderby('material_name','asc')->get();

        return view('user.assign_material.select_materials',$data);
    }

    public function select_material_stock(Request $request)
    {
        $stock = Stock::where('stock_material',$request->material_id)->first();

        if(isset($stock))
        {
            return response()->json(['available_qty' => $stock->stock_qty]);
        }
        else
        {
            return response()->json(['available_qty' => 0]);
        }
    }
}
