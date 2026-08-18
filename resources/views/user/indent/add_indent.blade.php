@extends('user.layouts.app')
@section('title', 'Add Indent - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Add Indent</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.indents') }}">Material Indents</a></li>
               <li class="breadcrumb-item active">Add Indent</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.add_indent') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.indents') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="indent_form" action="{{ route('user.add_indent') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Indent</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-2">
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="indent_date" class="form-control" value="{{ date('Y-m-d') }}" autocomplete="off">
                                 @if($errors->has('indent_date'))
                                 <div>{{ $errors->first('indent_date') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Project<span class="text-danger ms-1">*</span></label>
                                 <select name="indent_project" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('indent_project'))
                                 <div>{{ $errors->first('indent_project') }}</div>
                                 @endif
                                 <div id="indent_project_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Urgency<span class="text-danger ms-1">*</span></label>
                                 <select name="indent_urgency" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    <option value="1">Normal</option>
                                    <option value="2">High</option>
                                    <option value="3">Critical</option>
                                 </select>
                                 @if($errors->has('indent_urgency'))
                                 <div>{{ $errors->first('indent_urgency') }}</div>
                                 @endif
                                 <div id="indent_urgency_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Site Engineer<span class="text-danger ms-1">*</span></label>
                                 <select name="indent_engineer" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($engineers as $engineer)
                                    <option value="{{ $engineer->user_id }}">{{ $engineer->user_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('indent_engineer'))
                                 <div>{{ $errors->first('indent_engineer') }}</div>
                                 @endif
                                 <div id="indent_engineer_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Notes / Specifications<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="indent_notes" class="form-control" value="{{ old('indent_notes') }}" autocomplete="off">
                                 @if($errors->has('indent_notes'))
                                 <div>{{ $errors->first('indent_notes') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Note for Approval<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="indent_napproval" class="form-control" value="{{ old('indent_napproval') }}" autocomplete="off">
                                 @if($errors->has('indent_napproval'))
                                 <div>{{ $errors->first('indent_napproval') }}</div>
                                 @endif
                              </div>
                           </div>
                        </div>
                        <div class="row mb-3">
                           <div class="col-xl-12 col-md-12">
                              <table class="table table-bordered">
                                 <thead>
                                    <tr>
                                       <th style="width: 20%">Material</th>
                                       <th style="width: 20%">Category</th>
                                       <th>Size</th>
                                       <th>Quantity</th>
                                       <th>Unit</th>
                                       <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody id="more_material">
                                    <tr id="material_div1">
                                       <td>
                                          <select name="materials[NAME][1]" class="form-control mnames select2" data-toggle="select2">
                                             <option value="">Select</option>
                                             @foreach($materials as $material)
                                             <option value="{{ $material->material_id }}">{{ $material->material_name }}</option>
                                             @endforeach
                                          </select>
                                       </td>
                                       <td>
                                          <select name="materials[CATEGORY][1]" class="form-control mcategory select2" data-toggle="select2">
                                             <option value="">Select</option>
                                             @foreach($categories as $category)
                                             <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                             @endforeach
                                          </select>
                                       </td>
                                       <td>
                                          <input type="text" name="materials[SIZE][1]" class="form-control msizes" autocomplete="off">
                                       </td>
                                       <td>
                                          <input type="number" name="materials[QTY][1]" class="form-control mqtys" autocomplete="off">
                                       </td>
                                       <td>
                                          <select name="materials[UNIT][1]" class="form-control munits select2" data-toggle="select2">
                                             <option value="">Select</option>
                                             @foreach($units as $unit)
                                             <option value="{{ $unit->unit_id }}">{{ $unit->unit_name }}</option>
                                             @endforeach
                                          </select>
                                       </td>
                                       <td>
                                          <a href="javascript:void(0)" class="btn btn-success" onclick="more_material()"><i class="ti ti-plus"></i></a>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
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
<script>
var i = 2;

function more_material()
{
   $("#more_material").append('<tr id="material_div'+i+'"> <td> <select name="materials[NAME]['+i+']" class="form-control mnames select2" data-toggle="select2"> <option value="">Select</option> @foreach($materials as $material) <option value="{{ $material->material_id }}">{{ $material->material_name }}</option> @endforeach </select> </td> <td> <select name="materials[CATEGORY]['+i+']" class="form-control mcategory select2" data-toggle="select2"> <option value="">Select</option>@foreach($categories as $category)<option value="{{ $category->category_id }}">{{ $category->category_name }}</option>@endforeach</select></td><td><input type="text" name="materials[SIZE]['+i+']" class="form-control msizes" autocomplete="off"></td> <td> <input type="number" name="materials[QTY]['+i+']" class="form-control mqtys" autocomplete="off"> </td> <td> <select name="materials[UNIT]['+i+']" class="form-control munits select2" data-toggle="select2"> <option value="">Select</option> @foreach($units as $unit) <option value="{{ $unit->unit_id }}">{{ $unit->unit_name }}</option> @endforeach </select> </td> <td> <a href="javascript:void(0)" class="btn btn-danger" onclick="remove_material('+i+')"><i class="ti ti-minus"></i></a> </td> </tr>');

   $("#material_div"+i+" [data-toggle='select2']").select2({
      width: '100%'
   });

   i++;
}

function remove_material(rm)
{
   $("#material_div"+rm).remove();
}

$("#indent_form").submit(function (e) {

	$('.mnames').each(function() {
	    $(this).rules('add', {
	        required:true,
	        messages: {
	            required: '<small style="color:red">Please Select Material</small>'            
	        }
	    });
	});
   
   $('.mcategory').each(function() {
	    $(this).rules('add', {
	        required:true,
	        messages: {
	            required: '<small style="color:red">Please Select Category</small>'            
	        }
	    });
	});

   $('.msizes').each(function() {
	    $(this).rules('add', {
	        required:true,
	        messages: {
	            required: '<small style="color:red">Please Enter Size</small>'
	        }
	    });
	});

   $('.mqtys').each(function() {
	    $(this).rules('add', {
	        required:true,
	        messages: {
	            required: '<small style="color:red">Please Enter Quantity</small>'            
	        }
	    });
	});

   $('.munits').each(function() {
	    $(this).rules('add', {
	        required:true,
	        messages: {
	            required: '<small style="color:red">Please Select Unit</small>'            
	        }
	    });
	});

});	
</script>
@endsection
