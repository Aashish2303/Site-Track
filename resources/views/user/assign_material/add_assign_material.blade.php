@extends('user.layouts.app')
@section('title', 'Add Assign Material - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Add Assign Material</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.assign_materials') }}">Assign Materials</a></li>
               <li class="breadcrumb-item active">Add Assign Material</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.add_assign_material') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.assign_materials') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="assign_material_form" action="{{ route('user.add_assign_material') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Assign Material</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="amaterial_date" class="form-control" value="{{ old('amaterial_date') }}" autocomplete="off">
                                 @if($errors->has('amaterial_date'))
                                 <div>{{ $errors->first('amaterial_date') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Project<span class="text-danger ms-1">*</span></label>
                                 <select name="amaterial_project" id="amaterial_project" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('amaterial_project'))
                                 <div>{{ $errors->first('amaterial_project') }}</div>
                                 @endif
                                 <div id="amaterial_project_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Category<span class="text-danger ms-1">*</span></label>
                                 <select name="amaterial_category" id="amaterial_category" class="form-control select2" data-toggle="select2" data-st-change="select_materials()">
                                    <option value="">Select</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->mcategory_id }}">{{ $category->mcategory_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('amaterial_category'))
                                 <div>{{ $errors->first('amaterial_category') }}</div>
                                 @endif
                                 <div id="amaterial_category_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Material<span class="text-danger ms-1">*</span></label>
                                 <select name="amaterial_material" id="amaterial_material" class="form-control select2" data-toggle="select2" data-st-change="select_material_unit()">
                                    <option value="">Select</option>
                                 </select>
                                 @if($errors->has('amaterial_material'))
                                 <div>{{ $errors->first('amaterial_material') }}</div>
                                 @endif
                                 <div id="amaterial_material_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Unit<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="amaterial_unit_name" id="amaterial_unit_name" class="form-control" value="{{ old('amaterial_unit_name') }}" autocomplete="off" readonly>
                                 <input type="hidden" name="amaterial_unit" id="amaterial_unit" class="form-control" value="">
                                 @if($errors->has('amaterial_unit'))
                                 <div>{{ $errors->first('amaterial_unit') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Available Quantity<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="available_qty" id="available_qty" class="form-control" value="0" autocomplete="off" readonly>
                                 @if($errors->has('available_qty'))
                                 <div>{{ $errors->first('available_qty') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Quantity<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="amaterial_qty" id="amaterial_qty" class="form-control" value="{{ old('amaterial_qty') }}" autocomplete="off">
                                 @if($errors->has('amaterial_qty'))
                                 <div>{{ $errors->first('amaterial_qty') }}</div>
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
@section('custom_script')
<script nonce="{{ app(\App\Security\SecurityPolicy::class)->nonce() }}">
function select_materials()
{
   var category_id = $('#amaterial_category').val();

   $.ajax({
      url: "{{ route('user.select_materials') }}",
      type: "POST",
      data: {
            _token: "{{ csrf_token() }}",
            category_id: category_id
      },
      success: function(response)
      {
         $('#amaterial_material').html(response);
         select_material_unit();
      }
   });
}

function select_material_stock()
{
   var material_id = $('#amaterial_material').val();

   $.ajax({
      url: "{{ route('user.select_material_stock') }}",
      type: "POST",
      data: {
            _token: "{{ csrf_token() }}",
            material_id: material_id
      },
      success: function(response)
      {
         $('#available_qty').val(response.available_qty);
      }
   });
}

function select_material_unit()
{
   var material_id = $('#amaterial_material').val();

   $.ajax({
      url: "{{ route('user.select_material_unit') }}",
      type: "POST",
      data: {
            _token: "{{ csrf_token() }}",
            material_id: material_id
      },
      success: function(response)
      {
         $('#amaterial_unit_name').val(response.unit_name);
         $('#amaterial_unit').val(response.unit_id);

         select_material_stock();
      }
   });
}
</script>
@endsection
