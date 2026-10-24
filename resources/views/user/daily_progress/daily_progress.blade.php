@extends('user.layouts.app')
@section('title', 'Daily Progress - ' . Request()->settings->settings_title)
@section('custom_style')
<style>
.boq-row-progress-wrap {
   display: inline-flex;
   align-items: center;
   gap: .5rem;
   min-width: 125px;
}

.boq-row-progress {
   width: 80px;
   height: 8px;
   overflow: hidden;
   border-radius: 999px;
   background: #24304f;
   flex: 0 0 80px;
}

.boq-row-progress span {
   display: block;
   width: var(--boq-row-progress, 0%);
   height: 100%;
   border-radius: inherit;
   background: var(--boq-row-progress-color, #ffc221);
}

.boq-row-progress-percent {
   color: var(--boq-row-progress-color, #ffc221);
   font-size: .8125rem;
   font-weight: 800;
   white-space: nowrap;
}
</style>
@endsection
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Daily Progress</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">Daily Progress</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.daily_progress') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         @if(Helper::userHasPermission('user.progress_download_excel'))
         <a href="{{ route('user.progress_download_excel',['start_date'=>'','end_date'=>'','status'=>'']) }}" id="excel_download" class="btn btn-success"><i class="ti ti-download me-1"></i>Download</a>
         @endif
         @if(Helper::userHasPermission('user.add_daily_progress'))
         <a href="{{ route('user.add_daily_progress') }}" class="btn btn-primary"><i class="ti ti-square-rounded-plus me-1"></i>Add Daily Progress</a>
         @endif
      </div>
   </div>
   <div class="card mb-0">
      <div class="card-header d-flex align-items-center flex-wrap gap-2 justify-content-between">
         <h5 class="d-inline-flex align-items-center mb-0">Manage Daily Progress</h5>
      </div>
      <div class="card-body">
         <div class="row mb-4">
            <div class="col-md-2">
               <div class="mb-3">
                  <input type="date" name="start_date" id="start_date" class="form-control" onchange="filter_options()">
               </div>
            </div>
            <div class="col-md-2">
               <div class="mb-3">
                  <input type="date" name="end_date" id="end_date" class="form-control" onchange="filter_options()">
               </div>
            </div>
            <div class="col-md-2">
               <select name="status" id="status" class="select" onchange="filter_options()">
                  <option value="">Status</option>
                  <option value="1">Completed</option>
                  <option value="2">Partial</option>
                  <option value="3">Delayed - Carry Forward</option>
                  <option value="4">Cancelled</option>
                  <option value="5">Not Started</option>
               </select>
            </div>
         </div>
         <div class="table-responsive-sm">
            <table id="daily_progress_table" class="table border mb-0 w-100">
               <thead class="table-light">
                  <tr>
                     <th>#</th>
                     <th>Date</th>
                     <th>Project</th>
                     <th>Category</th>
                     <th>Description</th>
                     <th>BOQ</th>
                     <th>Reported quantity</th><th>Approved quantity</th><th>Verification</th>
                     <th>Reported %</th>
                     <th>Carry Forward</th>
                     <th>Status</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
<div id="statusModal" class="modal fade">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
            <div class="modal-header">
               <h5 class="text-dark modal-title fw-bold text-truncate">Status</h5>
               <button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-circle-x-filled"></i></button>
            </div>
            <form id="daily_progress_status_form" action="{{ route('user.add_daily_progress_status') }}" method="POST">
            </form>
      </div>
   </div>
</div>
@endsection
@section('custom_script')
<script>
var dataTable = $('#daily_progress_table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    autoWidth: false,
    dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
         "t" +
         "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    'ajax': {
            type : 'POST',
            url : "{{ route('user.get_daily_progress') }}",
            'data': function(data){

               var token = "{{ csrf_token() }}";
               var start_date = $('#start_date').val();
              	var end_date = $('#end_date').val();
               var status = $('#status').val();

               data._token = token;
               data.start_date = start_date;
               data.end_date = end_date;
               data.status = status;
            }
    },
    columns: [
        {data: 'DT_RowIndex'},
        {data: 'work_date'},
        {data: 'project_name'},
        {data: 'category_name'},
        {data: 'dprogress_description'},
        {data: 'boq_name'},
        {data: 'dprogress_qty'},
        {data: 'approved_quantity'},
        {data: 'verification'},
        {data: 'progress'},
        {data: 'carry_forward'},
        {data: 'status'},
        {data: 'action',orderable: false, searchable: false}
    ]
});

function filter_options()
{
   var start_date = $('#start_date').val();
   var end_date = $('#end_date').val();
   var status = $('#status').val();

   var excel_url = window.location.origin+'/user/progress_download_excel?start_date='+start_date+'&end_date='+end_date+'&status='+status;

   $("#excel_download").attr("href",excel_url);

   dataTable.draw();
}

function daily_progress_status(dprogress_id)
{
	var csrf = "{{ csrf_token() }}";

	$.ajax({
	    url: "{{ route('user.daily_progress_status') }}",
	    type: "post",
	    data: "dprogress_id="+dprogress_id+'&_token='+csrf,
	    success: function (data) {

         $('#statusModal').modal('show');
         $("#daily_progress_status_form").html(data);
	    }
	});
}

function confirm_msg(ev)
{
    ev.preventDefault();

    var urlToRedirect = ev.currentTarget.getAttribute('href');

    Swal.fire({
        title: "Are you sure?",
        text: "You want to change status!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, change it!",
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: !1,
        icon: "warning",
    }).then(function (t) {

        if(t.value)
        {
            window.stSubmitStatus(urlToRedirect);
        }
    });
}
</script>
@endsection
