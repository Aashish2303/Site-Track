@extends('user.layouts.app')
@section('title', 'Issues - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Issues</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">Issues</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.issues') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         @if(Helper::userHasPermission('user.issues_download_excel'))
         <a href="{{ route('user.issues_download_excel') }}" id="excel_download" class="btn btn-success"><i class="ti ti-download me-1"></i>Download</a>
         @endif
         @if(Helper::userHasPermission('user.add_issue'))
         <a href="{{ route('user.add_issue') }}" class="btn btn-primary"><i class="ti ti-square-rounded-plus me-1"></i>Add Issue</a>
         @endif
      </div>
   </div>
   <div class="row">
      <div class="col-xl-3 col-md-6 d-flex">
         <div class="card flex-fill labour-summary-card">
            <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                     <div>
                        <p class="mb-1">Open</p>
                        <h6 class="mb-0">{{ $total_open }}</h6>
                     </div>
                  </div>
            </div>
         </div>
      </div>
      <div class="col-xl-3 col-md-6 d-flex">
         <div class="card flex-fill labour-summary-card">
            <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                     <div>
                        <p class="mb-1">In Progress</p>
                        <h6 class="mb-0">{{ $total_inprogress }}</h6>
                     </div>
                  </div>
            </div>
         </div>
      </div>
      <div class="col-xl-3 col-md-6 d-flex">
         <div class="card flex-fill labour-summary-card">
            <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                     <div>
                        <p class="mb-1">Resolved</p>
                        <h6 class="mb-0">{{ $total_resolved }}</h6>
                     </div>
                  </div>
            </div>
         </div>
      </div>
      <div class="col-xl-3 col-md-6 d-flex">
         <div class="card flex-fill labour-summary-card">
            <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                     <div>
                        <p class="mb-1">Closed</p>
                        <h6 class="mb-0">{{ $total_closed }}</h6>
                     </div>
                  </div>
            </div>
         </div>
      </div>
   </div>
   <div class="card mb-0">
      <div class="card-header d-flex align-items-center flex-wrap gap-2 justify-content-between">
         <h5 class="d-inline-flex align-items-center mb-0">Manage Issues</h5>
      </div>
      <div class="card-body">
         <div class="table-responsive-sm">
            <table id="issue_table" class="table border mb-0 w-100">
               <thead class="table-light">
                  <tr>
                     <th>#</th>
                     <th>Date</th>
                     <th>Project</th>
                     <th>Title</th>
                     <th>Description</th>
                     <th>Category</th>
                     <th>Assigned To</th>
                     <th>Raised By</th>
                     <th>Priority</th>
                     <th>Status</th>
                     <th>Resolution Remarks</th>
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
            <form id="issue_status_form" action="{{ route('user.add_issue_status') }}" method="POST">
            </form>
      </div>
   </div>
</div>
@endsection
@section('custom_script')
<script nonce="{{ app(\App\Security\SecurityPolicy::class)->nonce() }}">
var dataTable = $('#issue_table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    autoWidth: false,
    dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
         "t" +
         "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    'ajax': {
            type : 'POST',
            url : "{{ route('user.get_issues') }}",
            'data': function(data){

                var token = "{{ csrf_token() }}";
                data._token = token;
            }
    },
    columns: [
        {data: 'DT_RowIndex'},
        {data: 'issue_date'},
        {data: 'project_name'},
        {data: 'issue_title'},
        {data: 'issue_description'},
        {data: 'category_name'},
        {data: 'assigned_to'},
        {data: 'raised_by'},
        {data: 'priority'},
        {data: 'status'},
        {data: 'issue_sremarks'},
        {data: 'action',orderable: false, searchable: false}
    ]
});

function issue_status(issue_id)
{
	var csrf = "{{ csrf_token() }}";

	$.ajax({
	    url: "{{ route('user.issue_status') }}",
	    type: "post",
	    data: "issue_id="+issue_id+'&_token='+csrf,
	    success: function (data) {

         $('#statusModal').modal('show');
         $("#issue_status_form").html(data);
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
