<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Excel;
use Helper;
use Validator;
use DataTables;
use App\Exports\SubcontractorLedgerExport;
use App\Models\Subcontractor;
use App\Models\Project;
use App\Models\SubcontractorLedger;

class SubcontractorLedgerController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['project' => 'nullable|integer|exists:projects,project_id']);
        $ledger_summaries = SubcontractorLedger::when($request->filled('project'), fn ($q) => $q->where('sledger_project', $request->project))->with('subcontractor')->get()->groupBy('sledger_subcontractor');

        $data['ledger_summaries'] = $ledger_summaries->map(function($ledgers) {

            $total = $ledgers->sum('sledger_total');
            $invoice = $ledgers->sum('sledger_invoice');
            $paid = $ledgers->sum('sledger_paid');
            $balance = $total - $paid;
            $paid_percent = $total > 0 ? round(($paid / $total) * 100) : 0;
            $paid_percent = max(0, min(100, $paid_percent));

            return [
                'subcontractor_name' => optional($ledgers->first()->subcontractor)->subcontractor_name,
                'entries' => $ledgers->count(),
                'total' => $total,
                'invoice' => $invoice,
                'paid' => $paid,
                'balance' => $balance,
                'paid_percent' => $paid_percent,
            ];
        })->sortByDesc('balance')->values();

        $data['set'] = 'subcontractor_ledger';
        return view('user.subcontractor_ledger.subcontractor_ledger',$data);
    }

    public function get_subcontractor_ledger(Request $request)
    {
        if($request->ajax())
        {
            $request->validate(['project' => 'nullable|integer|exists:projects,project_id']);
            $data = SubcontractorLedger::when($request->filled('project'), fn ($q) => $q->where('sledger_project', $request->project))->orderby('sledger_id','desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('ledger_date', function($row){

                        $ledger_date = date('d M Y',strtotime($row->sledger_date));

                        return $ledger_date;
                    })
                    ->addColumn('subcontractor_name', function($row){

                        $subcontractor_name = $row->subcontractor->subcontractor_name;

                        return $subcontractor_name;
                    })
                    ->addColumn('project_name', function($row){

                        $project_name = $row->project->project_name;

                        return $project_name;
                    })
                    ->addColumn('type_name', function($row){

                        if($row->sledger_type == 1)
                        {
                            $type_name = 'Work Order';
                        }
                        elseif($row->sledger_type == 2)
                        {
                            $type_name = 'Running Bill';
                        }
                        elseif($row->sledger_type == 3)
                        {
                            $type_name = 'Final Bill';
                        }
                        elseif($row->sledger_type == 4)
                        {
                            $type_name = 'Advance';
                        }
                        elseif($row->sledger_type == 5)
                        {
                            $type_name = 'Retention Release';
                        }
                        elseif($row->sledger_type == 6)
                        {
                            $type_name = 'Deduction';
                        }

                        return $type_name;
                    })
                    ->addColumn('total_amount', function($row){

                        $total_amount = Helper::moneyFormat($row->sledger_total);

                        return $total_amount;
                    })
                    ->addColumn('paid_amount', function($row){

                        $paid_amount = Helper::moneyFormat($row->sledger_paid);

                        return $paid_amount;
                    })
                    ->addColumn('balance', function($row){

                        $balance = $row->sledger_total - $row->sledger_paid;
                        $balance = Helper::moneyFormat($balance);

                        return $balance;
                    })
                    ->only(config('response-fields.'.request()->route()->getName(), []))
                    ->make(true);
        }
    }

    public function subcontractor_ledger_download_excel(Request $request)
    {
        $request->validate(['project' => 'nullable|integer|exists:projects,project_id']);
        $filename = 'subcontractor_ledger_'.date('Y_m_d_H_i_s').'.xlsx';

        return Excel::download(new SubcontractorLedgerExport($request->filled('project') ? (int) $request->project : null),$filename);
    }

    public function add_subcontractor_ledger(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['sledger_subcontractor' => 'required',
                      'sledger_project' => 'required',
                      'sledger_date' => 'required',
                      'sledger_type' => 'required',
                      'sledger_wpno' => 'required',
                      'sledger_description' => 'required',
                      'sledger_total' => 'required',
                      'sledger_invoice' => 'required',
                      'sledger_paid' => 'required'];

            $messages = ['sledger_subcontractor.required' => 'Please Select Subcontractor',
                         'sledger_project.required' => 'Please Select Project',
                         'sledger_date.required' => 'Please Select Date',
                         'sledger_type.required' => 'Please Select Type',
                         'sledger_wpno.required' => 'Please Enter WO / PO Number',
                         'sledger_description.required' => 'Please Enter Description',
                         'sledger_total.required' => 'Please Enter Total Amount',
                         'sledger_invoice.required' => 'Please Enter Invoice Amount',
                         'sledger_paid.required' => 'Please Enter Paid Amount'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $ins['sledger_subcontractor'] = $request->sledger_subcontractor;
                $ins['sledger_project']       = $request->sledger_project;
                $ins['sledger_date']          = $request->sledger_date;
                $ins['sledger_type']          = $request->sledger_type;
                $ins['sledger_wpno']          = $request->sledger_wpno;
                $ins['sledger_description']   = $request->sledger_description;
                $ins['sledger_total']         = $request->sledger_total;
                $ins['sledger_invoice']       = $request->sledger_invoice;
                $ins['sledger_paid']          = $request->sledger_paid;
                $ins['sledger_remarks']       = $request->sledger_remarks;
                $ins['sledger_added_on']      = date('Y-m-d H:i:s');
                $ins['sledger_added_by']      = Auth::user()->user_id;
                $ins['sledger_updated_on']    = date('Y-m-d H:i:s');
                $ins['sledger_updated_by']    = Auth::user()->user_id;
                $ins['sledger_status']        = 1;

                $add = SubcontractorLedger::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','Ledger Added Successfully');
                }
            }
        }

        $data['projects'] = Project::where('project_status',1)->orderby('project_name','asc')->get();

        $data['subcontractors'] = Subcontractor::where('subcontractor_status',1)->orderby('subcontractor_name','asc')->get();

        $data['set'] = 'subcontractor_ledger';
        return view('user.subcontractor_ledger.add_subcontractor_ledger',$data);
    }
}
