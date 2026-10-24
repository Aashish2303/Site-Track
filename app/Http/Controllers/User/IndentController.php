<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Excel;
use Helper;
use Validator;
use DataTables;
use App\Exports\IndentExport;
use App\Models\Project;
use App\Models\User;
use App\Models\Unit;
use App\Models\Material;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\Indent;
use App\Models\IndentItem;
use App\Models\IndentQuotation;

class IndentController extends Controller
{
    public function index()
    {
        $data['set'] = 'indents';
        return view('user.indent.indents',$data);
    }

    public function get_indents(Request $request)
    {
        if($request->ajax())
        {
            $data = Indent::orderby('indent_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('indent_date', function($row){

                        $indent_date = date('d M Y',strtotime($row->indent_date));

                        return $indent_date;
                    })
                    ->addColumn('project_name', function($row){

                        $project_name = $row->project->project_name;

                        return $project_name;
                    })
                    ->addColumn('engineer_name', function($row){

                        $engineer_name = $row->engineer->user_name;

                        return $engineer_name;
                    })
                    ->addColumn('total_quotations', function($row){

                        $total_quotations = IndentQuotation::where('iquote_indent',$row->indent_id)->count();

                        return $total_quotations;
                    })
                    ->addColumn('urgency', function($row){

                        if($row->indent_urgency == 1)
                        {
                            $urgency = '<span class="badge badge-normal">Normal</span>';
                        }
                        elseif($row->indent_urgency == 2)
                        {
                            $urgency = '<span class="badge badge-high">High</span>';
                        }
                        elseif($row->indent_urgency == 3)
                        {
                            $urgency = '<span class="badge badge-critical">Critical</span>';
                        }

                        return $urgency;
                    })
                    ->addColumn('status', function($row){

                        if($row->indent_status == 1)
                        {
                            $status = '<span class="badge badge-pending-quotation">Pending Quotation</span>';
                        }
                        elseif($row->indent_status == 2)
                        {
                            $status = '<span class="badge badge-md-review">MD Review</span>';
                        }
                        elseif($row->indent_status == 3)
                        {
                            $status = '<span class="badge badge-approved">Approved</span>';
                        }
                        elseif($row->indent_status == 4)
                        {
                            $status = '<span class="badge badge-rejected">Rejected</span>';
                        }

                        return $status;
                    })
                    ->addColumn('action', function($row){

                        $btn = '';

                        if(Helper::userHasPermission('user.download_indent'))
                        {
                            $btn .= '<a href="'.route('user.download_indent',['id'=>$row->indent_id]).'" class="btn btn-icon btn-sm btn-info" title="Download"><i class="ti ti-download"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.indent_items'))
                        {
                            $btn .= '<a href="'.route('user.indent_items',['id'=>$row->indent_id]).'" class="btn btn-icon btn-sm btn-dark" title="Items"><i class="ti ti-package"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.indent_quotations'))
                        {
                            $btn .= '<a href="'.route('user.indent_quotations',['id'=>$row->indent_id]).'" class="btn btn-icon btn-sm btn-secondary" title="Quotations"><i class="ti ti-file"></i></a> ';
                        }

                        if(Helper::userHasPermission('user.edit_indent'))
                        {
                            $btn .= '<a href="'.route('user.edit_indent',['id'=>$row->indent_id]).'" class="btn btn-icon btn-sm btn-success" title="Edit"><i class="ti ti-edit"></i></a> ';
                        }

                        return $btn;
                    })
                    ->rawColumns(['action','urgency','status'])
                    ->make(true);
        }
    }

    public function download_indent(Request $request)
    {
        $filename = 'indent_'.date('Y_m_d_H_i_s').'.xlsx';

        return Excel::download(new IndentExport($request->segment(3)),$filename);
    }

    public function add_indent(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['indent_date' => 'required',
                      'indent_project' => 'required',
                      'indent_urgency' => 'required',
                      'indent_engineer' => 'required',
                      'indent_notes' => 'required',
                      'indent_napproval' => 'required'];

            $messages = ['indent_date.required' => 'Please Select Date',
                         'indent_project.required' => 'Please Select Project',
                         'indent_urgency.required' => 'Please Select Urgency',
                         'indent_engineer.required' => 'Please Enter Site Engineer',
                         'indent_notes.required' => 'Please Enter Notes',
                         'indent_napproval.required' => 'Please Enter Note for Approval'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $ins['indent_date']       = $request->indent_date;
                $ins['indent_project']    = $request->indent_project;
                $ins['indent_urgency']    = $request->indent_urgency;
                $ins['indent_engineer']   = $request->indent_engineer;
                $ins['indent_notes']      = $request->indent_notes;
                $ins['indent_napproval']  = $request->indent_napproval;
                $ins['indent_added_on']   = date('Y-m-d H:i:s');
                $ins['indent_added_by']   = Auth::user()->user_id;
                $ins['indent_updated_on'] = date('Y-m-d H:i:s');
                $ins['indent_updated_by'] = Auth::user()->user_id;
                $ins['indent_status']     = 1;

                $add = Indent::create($ins);

                if($add)
                {
                    if($request->has('materials') && !empty($request->materials))
                    {
                        $materials = $request->materials;

                        foreach($materials['NAME'] as $key => $material)
                        {
                            $ins_item['iitem_indent']     = $add->indent_id;
                            $ins_item['iitem_item']       = $materials['NAME'][$key];
                            $ins_item['iitem_category']   = $materials['CATEGORY'][$key];
                            $ins_item['iitem_size']       = $materials['SIZE'][$key];
                            $ins_item['iitem_qty']        = $materials['QTY'][$key];
                            $ins_item['iitem_unit']       = $materials['UNIT'][$key];
                            $ins_item['iitem_added_on']   = date('Y-m-d H:i:s');
                            $ins_item['iitem_added_by']   = Auth::user()->user_id;
                            $ins_item['iitem_updated_on'] = date('Y-m-d H:i:s');
                            $ins_item['iitem_updated_by'] = Auth::user()->user_id;
                            $ins_item['iitem_status']     = 1;

                            IndentItem::create($ins_item);
                        }
                    }

                    return redirect()->back()->with('success','Material Indent Added Successfully');
                }
            }
        }

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $where_engineer['user_role'] = 3;
        $where_engineer['user_status'] = 1;
        $data['engineers'] = User::where($where_engineer)->orderby('user_name','asc')->get();

        $data['materials'] = Material::where('material_status',1)->orderby('material_name','asc')->get();

        $data['categories'] = Category::where('category_status',1)->orderby('category_name','asc')->get();

        $data['units'] = Unit::where('unit_status',1)->orderby('unit_name','asc')->get();

        $data['set'] = 'indents';
        return view('user.indent.add_indent',$data);
    }

    public function edit_indent(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['indent_date' => 'required',
                      'indent_project' => 'required',
                      'indent_urgency' => 'required',
                      'indent_engineer' => 'required',
                      'indent_notes' => 'required',
                      'indent_napproval' => 'required'];

            $messages = ['indent_date.required' => 'Please Select Date',
                         'indent_project.required' => 'Please Select Project',
                         'indent_urgency.required' => 'Please Select Urgency',
                         'indent_engineer.required' => 'Please Enter Site Engineer',
                         'indent_notes.required' => 'Please Enter Notes',
                         'indent_napproval.required' => 'Please Enter Note for Approval'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $upd['indent_date']       = $request->indent_date;
                $upd['indent_project']    = $request->indent_project;
                $upd['indent_urgency']    = $request->indent_urgency;
                $upd['indent_engineer']   = $request->indent_engineer;
                $upd['indent_notes']      = $request->indent_notes;
                $upd['indent_napproval']  = $request->indent_napproval;
                $upd['indent_updated_on'] = date('Y-m-d H:i:s');
                $upd['indent_updated_by'] = Auth::user()->user_id;

                Indent::where('indent_id',$request->segment(3))->update($upd);

                return redirect()->back()->with('success','Material Indent Updated Successfully');
            }
        }

        $data['indent'] = Indent::where('indent_id',$request->segment(3))->first();

        if(!isset($data['indent']))
        {
            return redirect()->route('user.indents');
        }

        $data['items'] = IndentItem::where('iitem_indent',$request->segment(3))->get();

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $where_engineer['user_role'] = 3;
        $where_engineer['user_status'] = 1;
        $data['engineers'] = User::where($where_engineer)->orderby('user_name','asc')->get();

        $data['materials'] = Material::where('material_status',1)->orderby('material_name','asc')->get();

        $data['categories'] = Category::where('category_status',1)->orderby('category_name','asc')->get();

        $data['units'] = Unit::where('unit_status',1)->orderby('unit_name','asc')->get();

        $data['set'] = 'indents';
        return view('user.indent.edit_indent',$data);
    }

    public function indent_items(Request $request)
    {
        $data['indent'] = Indent::where('indent_id',$request->segment(3))->first();

        if(!isset($data['indent']))
        {
            return redirect()->route('user.indents');
        }

        $data['items'] = IndentItem::where('iitem_indent',$request->segment(3))->get();

        $data['set'] = 'indents';
        return view('user.indent.indent_items',$data);
    }

    public function indent_item_status(Request $request)
    {
        $data['item'] = IndentItem::where('iitem_id',$request->item_id)->first();

        $data['set'] = 'indents';
        return view('user.indent.indent_item_status',$data);
    }

    public function add_indent_item_status(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['status' => 'required'];

            $messages = ['status.required' => 'Please Select Status'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $upd['iitem_status']     = $request->status;
                $upd['iitem_updated_on'] = date('Y-m-d H:i:s');
                $upd['iitem_updated_by'] = Auth::user()->user_id;

                IndentItem::where('iitem_id',$request->item_id)->update($upd);

                return redirect()->back()->with('success','Status Updated Successfully');
            }
        }
    }

    public function indent_quotations(Request $request)
    {
        $data['indent'] = Indent::where('indent_id',$request->segment(3))->first();

        if(!isset($data['indent']))
        {
            return redirect()->route('user.indents');
        }

        $data['vendors'] = Vendor::where('vendor_status',1)->orderby('vendor_name','asc')->get();

        $data['quotations'] = IndentQuotation::where('iquote_indent',$data['indent']->indent_id)->get();

        $data['set'] = 'indents';
        return view('user.indent.indent_quotations',$data);
    }

    public function add_quotation(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['iquote_vendor' => 'required',
                      'iquote_rate' => 'required',
                      'iquote_total' => 'required',
                      'iquote_delivery' => 'required'];

            $messages = ['iquote_vendor.required' => 'Please Select Vendor',
                         'iquote_rate.required' => 'Please Enter Rate',
                         'iquote_total.required' => 'Please Enter Total',
                         'iquote_delivery.required' => 'Please Enter Delivery'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $total_quotations = IndentQuotation::where('iquote_indent',$request->segment(3))->count();

                if($total_quotations >= 1)
                {
                    $upd_status['indent_status'] = 2;
                    Indent::where('indent_id',$request->segment(3))->update($upd_status);
                }

                $ins['iquote_indent']     = $request->segment(3);
                $ins['iquote_vendor']     = $request->iquote_vendor;
                $ins['iquote_rate']       = $request->iquote_rate;
                $ins['iquote_total']      = $request->iquote_total;
                $ins['iquote_delivery']   = $request->iquote_delivery;
                $ins['iquote_notes']      = $request->iquote_notes;
                $ins['iquote_added_on']   = date('Y-m-d H:i:s');
                $ins['iquote_added_by']   = Auth::user()->user_id;
                $ins['iquote_updated_on'] = date('Y-m-d H:i:s');
                $ins['iquote_updated_by'] = Auth::user()->user_id;
                $ins['iquote_status']     = 1;

                if($request->hasFile('iquote_quotation'))
                {
                    $iquote_quotation = $request->iquote_quotation->store('assets/admin/uploads/quotation');

                    $iquote_quotation = explode('/',$iquote_quotation);
                    $iquote_quotation = end($iquote_quotation);
                    $ins['iquote_quotation'] = $iquote_quotation;
                }

                $add = IndentQuotation::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','Quotation Added Successfully');
                }
            }
        }
    }

    public function reject_quotations(Request $request)
    {
        $upd['indent_status'] = 4;
        $update = Indent::where('indent_id',$request->segment(3))->update($upd);

        if($update)
        {
            return redirect()->back()->with('success','Quotations Rejected Successfully');
        }
    }

    public function status_quotation(Request $request)
    {
        $upd['iquote_status'] = $request->segment(4);
        $update = IndentQuotation::where('iquote_id',$request->segment(3))->update($upd);

        if($update)
        {
            $quotation = IndentQuotation::where('iquote_id',$request->segment(3))->first();

            $upd_status['indent_status'] = 3;
            Indent::where('indent_id',$quotation->iquote_indent)->update($upd_status);

            return redirect()->back()->with('success','Status Updated Successfully');
        }
    }
}
