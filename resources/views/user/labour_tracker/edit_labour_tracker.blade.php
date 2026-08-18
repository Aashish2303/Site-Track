@extends('user.layouts.app')
@section('title', 'Edit Labour Tracker - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Edit Labour Tracker</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.labour_trackers') }}">Labour Tracker</a></li>
               <li class="breadcrumb-item active">Edit Labour Tracker</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.edit_labour_tracker',['id'=>$labour_tracker->ltracker_id]) }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.labour_trackers') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="labour_tracker_form" action="{{ route('user.edit_labour_tracker',['id'=>$labour_tracker->ltracker_id]) }}" method="POST">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Edit Labour Tracker</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="ltracker_date" class="form-control" value="{{ $labour_tracker->ltracker_date }}" autocomplete="off">
                                 @if($errors->has('ltracker_date'))
                                 <div>{{ $errors->first('ltracker_date') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Project<span class="text-danger ms-1">*</span></label>
                                 <select name="ltracker_project" id="ltracker_project" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}" {{ $labour_tracker->ltracker_project == $project->project_id ? 'selected':'' }}>{{ $project->project_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('ltracker_project'))
                                 <div>{{ $errors->first('ltracker_project') }}</div>
                                 @endif
                                 <div id="ltracker_project_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Subcontractor<span class="text-danger ms-1">*</span></label>
                                 <select name="ltracker_subcontractor" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($subcontractors as $subcontractor)
                                    <option value="{{ $subcontractor->subcontractor_id }}" {{ $labour_tracker->ltracker_subcontractor == $subcontractor->subcontractor_id ? 'selected':'' }}>{{ $subcontractor->subcontractor_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('ltracker_subcontractor'))
                                 <div>{{ $errors->first('ltracker_subcontractor') }}</div>
                                 @endif
                                 <div id="ltracker_subcontractor_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Site Incharge<span class="text-danger ms-1">*</span></label>
                                 <select name="ltracker_incharge" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($incharges as $incharge)
                                    <option value="{{ $incharge->user_id }}" {{ $labour_tracker->ltracker_incharge == $incharge->user_id ? 'selected':'' }}>{{ $incharge->user_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('ltracker_incharge'))
                                 <div>{{ $errors->first('ltracker_incharge') }}</div>
                                 @endif
                                 <div id="ltracker_incharge_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Shift<span class="text-danger ms-1">*</span></label>
                                 <select name="ltracker_shift" id="ltracker_shift" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    <option value="1" {{ $labour_tracker->ltracker_shift == 1 ? 'selected':'' }}>Day</option>
                                    <option value="2" {{ $labour_tracker->ltracker_shift == 2 ? 'selected':'' }}>Night</option>
                                 </select>
                                 @if($errors->has('ltracker_shift'))
                                 <div>{{ $errors->first('ltracker_shift') }}</div>
                                 @endif
                                 <div id="ltracker_shift_error"></div>   
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Remarks</label>
                                 <input type="text" name="ltracker_remarks" class="form-control" value="{{ $labour_tracker->ltracker_remarks }}" autocomplete="off">
                                 @if($errors->has('ltracker_remarks'))
                                 <div>{{ $errors->first('ltracker_remarks') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">In Time<span class="text-danger ms-1">*</span></label>
                                 <div class="input-group">
                                    <input type="time" name="ltracker_in" id="ltracker_in" class="form-control" value="{{ $labour_tracker->ltracker_in }}" autocomplete="off">
                                    <button type="button" class="btn btn-outline-primary" onclick="setCurrentTime('ltracker_in')">Now</button>
                                 </div>
                                 @if($errors->has('ltracker_in'))
                                 <div>{{ $errors->first('ltracker_in') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Out Time<span class="text-danger ms-1">*</span></label>
                                 <div class="input-group">
                                    <input type="time" name="ltracker_out" id="ltracker_out" class="form-control" value="{{ $labour_tracker->ltracker_out }}" autocomplete="off">
                                    <button type="button" class="btn btn-outline-primary" onclick="setCurrentTime('ltracker_out')">Now</button>
                                 </div>
                                 @if($errors->has('ltracker_out'))
                                 <div>{{ $errors->first('ltracker_out') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Working Hours<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="ltracker_thours" id="ltracker_thours" class="form-control" value="{{ $labour_tracker->ltracker_thours }}" autocomplete="off" readonly>
                                 @if($errors->has('ltracker_thours'))
                                 <div>{{ $errors->first('ltracker_thours') }}</div>
                                 @endif
                              </div>
                           </div>
                        </div>
                        <div class="row mb-3">
                           <div class="col-xl-12 col-md-12">
                              <table class="table table-bordered">
                                 <thead>
                                    <tr>
                                       <th style="width: 20%">Category</th>
                                       <th style="width: 20%">Skill</th>
                                       <th>Male</th>
                                       <th>Female</th>
                                       <th>Wage/Worker (₹)</th>
                                       <th>Total Cost (₹)</th>
                                       <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody id="more_worker">
                                    @foreach($workers as $worker)
                                    <tr id="worker_div{{ $loop->iteration }}">
                                       <td>
                                          <select name="workers[CATEGORY][{{ $loop->iteration }}]" class="form-control wcategory select2" data-toggle="select2">
                                             <option value="">Select</option>
                                             @foreach($categories as $category)
                                             <option value="{{ $category->category_id }}" {{ $worker->lworker_category == $category->category_id ? 'selected':'' }}>{{ $category->category_name }}</option>
                                             @endforeach
                                          </select>
                                       </td>
                                       <td>
                                          <select name="workers[SKILL][{{ $loop->iteration }}]" class="form-control wskill select2" data-toggle="select2">
                                             <option value="">Select</option>
                                             <option value="1" {{ $worker->lworker_skill == 1 ? 'selected':'' }}>Skilled</option>
                                             <option value="2" {{ $worker->lworker_skill == 2 ? 'selected':'' }}>Unskilled</option>
                                             <option value="3" {{ $worker->lworker_skill == 3 ? 'selected':'' }}>Semi Skilled</option>
                                          </select>
                                       </td>
                                       <td>
                                          <input type="number" name="workers[MALE][{{ $loop->iteration }}]" id="tmale_{{ $loop->iteration }}" class="form-control wmale" value="{{ $worker->lworker_male }}" onkeyup="total_calculation({{ $loop->iteration }})" autocomplete="off">
                                       </td>
                                       <td>
                                          <input type="number" name="workers[FEMALE][{{ $loop->iteration }}]" id="tfemale_{{ $loop->iteration }}" class="form-control wfemale" value="{{ $worker->lworker_female }}" onkeyup="total_calculation({{ $loop->iteration }})" autocomplete="off">
                                       </td>
                                       <td>
                                          <input type="number" name="workers[WAGE][{{ $loop->iteration }}]" id="twage_{{ $loop->iteration }}" class="form-control wwage" value="{{ $worker->lworker_wage }}" onkeyup="total_calculation({{ $loop->iteration }})" autocomplete="off">
                                       </td>
                                       <td>
                                          <input type="text" name="workers[TOTAL][{{ $loop->iteration }}]" id="tcost_{{ $loop->iteration }}" class="form-control total_salary" value="{{ $worker->lworker_total }}" readonly>
                                       </td>
                                       <td>
                                          @if($loop->iteration == 1)
                                          <a href="javascript:void(0)" class="btn btn-success" onclick="more_worker()"><i class="ti ti-plus"></i></a>
                                          @else
                                          <a href="javascript:void(0)" class="btn btn-danger" onclick="remove_worker({{ $loop->iteration }})"><i class="ti ti-minus"></i></a>
                                          @endif
                                       </td>
                                    </tr>
                                    @endforeach
                                 </tbody>
                                 <tfoot>
                                    <tr>
                                       <td style="text-align: right;padding-right:20px" colspan="5"><b>Grand Total (₹)</b></td>
                                       <td><input type="text" name="grand_total" id="grand_total" class="form-control" value="{{ $labour_tracker->ltracker_total }}" readonly></td>
                                       <td></td>
                                    </tr>
                                 </tfoot>
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
var i = {{ $workers->count() + 1 }};

function more_worker()
{
   $("#more_worker").append('<tr id="worker_div'+i+'"> <td> <select name="workers[CATEGORY]['+i+']" class="form-control wcategory select2" data-toggle="select2"> <option value="">Select</option> @foreach($categories as $category) <option value="{{ $category->category_id }}">{{ $category->category_name }}</option> @endforeach </select> </td> <td> <select name="workers[SKILL]['+i+']" class="form-control wskill select2" data-toggle="select2"> <option value="">Select</option> <option value="1">Skilled</option> <option value="2">Unskilled</option> <option value="3">Semi Skilled</option> </select> </td> <td> <input type="number" name="workers[MALE]['+i+']" id="tmale_'+i+'" class="form-control wmale" onkeyup="total_calculation('+i+')" autocomplete="off"> </td> <td> <input type="number" name="workers[FEMALE]['+i+']" id="tfemale_'+i+'" class="form-control wfemale" onkeyup="total_calculation('+i+')" autocomplete="off"> </td> <td> <input type="number" name="workers[WAGE]['+i+']" id="twage_'+i+'" class="form-control wwage" onkeyup="total_calculation('+i+')" autocomplete="off"> </td> <td> <input type="text" name="workers[TOTAL]['+i+']" id="tcost_'+i+'" class="form-control total_salary" readonly> </td> <td> <a href="javascript:void(0)" class="btn btn-danger" onclick="remove_worker('+i+')"><i class="ti ti-minus"></i></a> </td> </tr>');

   $("#worker_div"+i+" [data-toggle='select2']").select2({
      width: '100%'
   });

   i++;
}

function remove_worker(ri)
{
   $("#worker_div"+ri).remove();
}

function total_calculation(r)
{
   var male_count = $("#tmale_"+r).val();
   var female_count = $("#tfemale_"+r).val();
   var wage = $("#twage_"+r).val();

   if(male_count == ''){ male_count = 0; }
   if(female_count == ''){ female_count = 0; }
   if(wage == ''){ wage = 0; }

   var total_amount = (parseFloat(male_count) + parseFloat(female_count)) * parseFloat(wage);

   $("#tcost_"+r).val(total_amount);

   final_cost();
}

function final_cost()
{
   var grand_total_value = 0;

   $(".total_salary").each(function() {
      grand_total_value += parseFloat($(this).val());
   });

   $("#grand_total").val(grand_total_value);
}

function setCurrentTime(fieldId)
{
   var now = new Date();
   var hours = String(now.getHours()).padStart(2, '0');
   var minutes = String(now.getMinutes()).padStart(2, '0');

   $('#' + fieldId).val(hours + ':' + minutes).trigger('change');
}

$(document).ready(function () {
   function timeToMinutes(time) {
      if (!time) {
         return null;
      }

      var parts = time.split(':');
      return (parseInt(parts[0], 10) * 60) + parseInt(parts[1], 10);
   }

   function calculateWorkingHours() {
      var inMinutes = timeToMinutes($('#ltracker_in').val());
      var outMinutes = timeToMinutes($('#ltracker_out').val());

      if (inMinutes === null || outMinutes === null) {
         $('#ltracker_thours').val('');
         return;
      }

      if (outMinutes < inMinutes) {
         outMinutes += 24 * 60;
      }

      var totalHours = (outMinutes - inMinutes) / 60;
      $('#ltracker_thours').val(totalHours.toFixed(2));
   }

   $('#ltracker_in, #ltracker_out').on('change keyup', calculateWorkingHours);
   calculateWorkingHours();
});

$("#labour_tracker_form").submit(function (e) {
       
   $('.wcategory').each(function() {

      $(this).rules('add', {
         required:true,
         messages: {
               required: '<small style="color:red">Please Select Category</small>'
         }
      });

   });

   $('.wskill').each(function() {

      $(this).rules('add', {
         required:true,
         messages: {
               required: '<small style="color:red">Please Select Skill</small>'
         }
      });

   });

   $('.wmale').each(function() {

      $(this).rules('add', {
         required:true,
         messages: {
               required: '<small style="color:red">Please Enter Male</small>'
         }
      });

   });

   $('.wfemale').each(function() {

      $(this).rules('add', {
         required:true,
         messages: {
               required: '<small style="color:red">Please Enter Female</small>'
         }
      });

   });

   $('.wwage').each(function() {

      $(this).rules('add', {
         required:true,
         messages: {
               required: '<small style="color:red">Please Enter Wage</small>'
         }
      });

   });
});
</script>
@endsection
