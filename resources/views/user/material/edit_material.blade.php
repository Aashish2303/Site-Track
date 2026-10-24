@extends('user.layouts.app')
@section('title', 'Edit Material - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Edit Material</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.materials') }}">Materials</a></li>
               <li class="breadcrumb-item active">Edit Material</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.edit_material',['id'=>$material->material_id]) }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.materials') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="material_form" action="{{ route('user.edit_material',['id'=>$material->material_id]) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Edit Material</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Category<span class="text-danger ms-1">*</span></label>
                                 <select name="material_category" id="material_category" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->mcategory_id }}" {{ $material->material_category == $category->mcategory_id ? 'selected':'' }}>{{ $category->mcategory_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('material_category'))
                                 <div>{{ $errors->first('material_category') }}</div>
                                 @endif
                                 <div id="material_category_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Material<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="material_name" class="form-control" value="{{ $material->material_name }}" autocomplete="off">
                                 @if($errors->has('material_name'))
                                 <div>{{ $errors->first('material_name') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Unit<span class="text-danger ms-1">*</span></label>
                                 <select name="material_unit" id="material_unit" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($units as $unit)
                                    <option value="{{ $unit->unit_id }}" {{ $material->material_unit == $unit->unit_id ? 'selected':'' }}>{{ $unit->unit_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('material_unit'))
                                 <div>{{ $errors->first('material_unit') }}</div>
                                 @endif
                                 <div id="material_unit_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">HSN Code</label>
                                 <input type="text" name="material_hsn" class="form-control" value="{{ $material->material_hsn }}" autocomplete="off">
                                 @if($errors->has('material_hsn'))
                                 <div>{{ $errors->first('material_hsn') }}</div>
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
