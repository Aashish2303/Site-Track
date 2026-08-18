@extends('user.layouts.app')
@section('title', 'Add Vendor Ledger - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Add Vendor Ledger</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.vendor_ledger') }}">Vendor Ledger</a></li>
               <li class="breadcrumb-item active">Add Vendor Ledger</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.add_vendor_ledger') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.vendor_ledger') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="vendor_ledger_form" action="{{ route('user.add_vendor_ledger') }}" method="POST">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Vendor Ledger</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="vledger_date" class="form-control" value="{{ date('Y-m-d') }}" autocomplete="off">
                                 @if($errors->has('vledger_date'))
                                 <div>{{ $errors->first('vledger_date') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Vendor<span class="text-danger ms-1">*</span></label>
                                 <select name="vledger_vendor" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_company }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('vledger_vendor'))
                                 <div>{{ $errors->first('vledger_vendor') }}</div>
                                 @endif
                                 <div id="vledger_vendor_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Project<span class="text-danger ms-1">*</span></label>
                                 <select name="vledger_project" id="vledger_project" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('vledger_project'))
                                 <div>{{ $errors->first('vledger_project') }}</div>
                                 @endif
                                 <div id="vledger_project_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Type<span class="text-danger ms-1">*</span></label>
                                 <select name="vledger_type" id="vledger_type" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    <option value="1">Purchase</option>
                                    <option value="2">Advance</option>
                                    <option value="3">Return</option>
                                    <option value="4">Debit Note</option>
                                    <option value="5">Credit Note</option>
                                 </select>
                                 @if($errors->has('vledger_type'))
                                 <div>{{ $errors->first('vledger_type') }}</div>
                                 @endif
                                 <div id="vledger_type_error"></div>   
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">PO Number<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="vledger_pono" class="form-control" value="{{ old('vledger_pono') }}" autocomplete="off">
                                 @if($errors->has('vledger_pono'))
                                 <div>{{ $errors->first('vledger_pono') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Description<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="vledger_description" class="form-control" value="{{ old('vledger_description') }}" autocomplete="off">
                                 @if($errors->has('vledger_description'))
                                 <div>{{ $errors->first('vledger_description') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">PO Amount (₹)<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="vledger_po_total" class="form-control" value="{{ old('vledger_po_total') }}" autocomplete="off">
                                 @if($errors->has('vledger_po_total'))
                                 <div>{{ $errors->first('vledger_po_total  ') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Invoice Amount (₹)<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="vledger_invoice" class="form-control" value="{{ old('vledger_invoice') }}" autocomplete="off">
                                 @if($errors->has('vledger_invoice'))
                                 <div>{{ $errors->first('vledger_invoice') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Paid Amount (₹)<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="vledger_paid" class="form-control" value="{{ old('vledger_paid') }}" autocomplete="off">
                                 @if($errors->has('vledger_paid'))
                                 <div>{{ $errors->first('vledger_paid') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Remarks</label>
                                 <input type="text" name="vledger_remarks" class="form-control" value="{{ old('vledger_remarks') }}" autocomplete="off">
                                 @if($errors->has('vledger_remarks'))
                                 <div>{{ $errors->first('vledger_remarks') }}</div>
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