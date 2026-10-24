@extends('user.layouts.app')
@section('title', 'Add Vendor - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Add Vendor</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.vendors') }}">Vendors</a></li>
               <li class="breadcrumb-item active">Add Vendor</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.add_vendor') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.vendors') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="vendor_form" action="{{ route('user.add_vendor') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Vendor</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Category<span class="text-danger ms-1">*</span></label>
                                 <select name="vendor_category" id="vendor_category" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->mcategory_id }}">{{ $category->mcategory_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('vendor_category'))
                                 <div>{{ $errors->first('vendor_category') }}</div>
                                 @endif
                                 <div id="vendor_category_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="vendor_name" class="form-control" value="{{ old('vendor_name') }}" autocomplete="off">
                                 @if($errors->has('vendor_name'))
                                 <div>{{ $errors->first('vendor_name') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Company<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="vendor_company" class="form-control" value="{{ old('vendor_company') }}" autocomplete="off">
                                 @if($errors->has('vendor_company'))
                                 <div>{{ $errors->first('vendor_company') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Mobile No<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="vendor_mobile" class="form-control" value="{{ old('vendor_mobile') }}" autocomplete="off">
                                 @if($errors->has('vendor_mobile'))
                                 <div>{{ $errors->first('vendor_mobile') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Alternate No</label>
                                 <input type="text" name="vendor_altno" class="form-control" value="{{ old('vendor_altno') }}" autocomplete="off">
                                 @if($errors->has('vendor_altno'))
                                 <div>{{ $errors->first('vendor_altno') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Location<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="vendor_location" class="form-control" value="{{ old('vendor_location') }}" autocomplete="off">
                                 @if($errors->has('vendor_location'))
                                 <div>{{ $errors->first('vendor_location') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Material/Service Type<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="vendor_type" class="form-control" value="{{ old('vendor_type') }}" autocomplete="off">
                                 @if($errors->has('vendor_type'))
                                 <div>{{ $errors->first('vendor_type') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Remarks</label>
                                 <input type="text" name="vendor_remarks" class="form-control" value="{{ old('vendor_remarks') }}" autocomplete="off">
                                 @if($errors->has('vendor_remarks'))
                                 <div>{{ $errors->first('vendor_remarks') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Visiting Card</label>
                                 <input type="file" name="vendor_card" class="form-control">
                                 @if($errors->has('vendor_card'))
                                 <div>{{ $errors->first('vendor_card') }}</div>
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
