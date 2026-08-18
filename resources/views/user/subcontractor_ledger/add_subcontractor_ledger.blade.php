@extends('user.layouts.app')
@section('title', 'Add Subcontractor Ledger - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Add Subcontractor Ledger</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.subcontractor_ledger') }}">Subcontractor Ledger</a></li>
               <li class="breadcrumb-item active">Add Subcontractor Ledger</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.add_subcontractor_ledger') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.subcontractor_ledger') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="subcontractor_ledger_form" action="{{ route('user.add_subcontractor_ledger') }}" method="POST">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Subcontractor Ledger</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="sledger_date" class="form-control" value="{{ date('Y-m-d') }}" autocomplete="off">
                                 @if($errors->has('sledger_date'))
                                 <div>{{ $errors->first('sledger_date') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Subcontractor<span class="text-danger ms-1">*</span></label>
                                 <select name="sledger_subcontractor" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($subcontractors as $subcontractor)
                                    <option value="{{ $subcontractor->subcontractor_id }}">{{ $subcontractor->subcontractor_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('sledger_subcontractor'))
                                 <div>{{ $errors->first('sledger_subcontractor') }}</div>
                                 @endif
                                 <div id="sledger_subcontractor_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Project<span class="text-danger ms-1">*</span></label>
                                 <select name="sledger_project" id="sledger_project" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('sledger_project'))
                                 <div>{{ $errors->first('sledger_project') }}</div>
                                 @endif
                                 <div id="sledger_project_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Type<span class="text-danger ms-1">*</span></label>
                                 <select name="sledger_type" id="sledger_type" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    <option value="1">Work Order</option>
                                    <option value="2">Running Bill</option>
                                    <option value="3">Final Bill</option>
                                    <option value="4">Advance</option>
                                    <option value="5">Retention Release</option>
                                    <option value="6">Deduction</option>
                                 </select>
                                 @if($errors->has('sledger_type'))
                                 <div>{{ $errors->first('sledger_type') }}</div>
                                 @endif
                                 <div id="sledger_type_error"></div>   
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">WO / PO Number<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="sledger_wpno" class="form-control" value="{{ old('sledger_wpno') }}" autocomplete="off">
                                 @if($errors->has('sledger_wpno'))
                                 <div>{{ $errors->first('sledger_wpno') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Description<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="sledger_description" class="form-control" value="{{ old('sledger_description') }}" autocomplete="off">
                                 @if($errors->has('sledger_description'))
                                 <div>{{ $errors->first('sledger_description') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Total Amount (₹)<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="sledger_total" class="form-control" value="{{ old('sledger_total') }}" autocomplete="off">
                                 @if($errors->has('sledger_total'))
                                 <div>{{ $errors->first('sledger_total  ') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Invoice Amount (₹)<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="sledger_invoice" class="form-control" value="{{ old('sledger_invoice') }}" autocomplete="off">
                                 @if($errors->has('sledger_invoice'))
                                 <div>{{ $errors->first('sledger_invoice  ') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Paid Amount (₹)<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="sledger_paid" class="form-control" value="{{ old('sledger_paid') }}" autocomplete="off">
                                 @if($errors->has('sledger_paid'))
                                 <div>{{ $errors->first('sledger_paid') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Remarks</label>
                                 <input type="text" name="sledger_remarks" class="form-control" value="{{ old('sledger_remarks') }}" autocomplete="off">
                                 @if($errors->has('sledger_remarks'))
                                 <div>{{ $errors->first('sledger_remarks') }}</div>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="d-flex justify-content-end flex-wrap align-items-center gap-2">
                     <button type="submit" name="submit" class="btn btn-primary" id="save-basic-info" value="submit">Submit</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection