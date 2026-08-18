@extends('user.layouts.app')
@section('title', 'Projects - ' . Request()->settings->settings_title)
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
         <h4 class="mb-1">Projects</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">Projects</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.projects') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         @if(Helper::userHasPermission('user.add_project'))
         <a href="{{ route('user.add_project') }}" class="btn btn-primary"><i class="ti ti-square-rounded-plus me-1"></i>Add Project</a>
         @endif
      </div>
   </div>
   <div class="card mb-0">
      <div class="card-header d-flex align-items-center flex-wrap gap-2 justify-content-between">
         <h5 class="d-inline-flex align-items-center mb-0">Manage Projects</h5>
      </div>
      <div class="card-body">
         <div class="table-responsive-sm">
            <table id="project_table" class="table border mb-0 w-100">
               <thead class="table-light">
                  <tr>
                     <th>#</th>
                     <th>Project</th>
                     <th>Location</th>
                     <th>Client</th>
                     <th>Site Engineer</th>
                     <th>Period</th>
                     <th>BOQ Items</th>
                     <th>Budget (₹)</th>
                     <th>Progress</th>
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
            <form id="project_status_form" action="{{ route('user.add_project_status') }}" method="POST">
            </form>
      </div>
   </div>
</div>
@endsection
@section('custom_script')
<script>
var dataTable = $('#project_table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    autoWidth: false,
    dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
         "t" +
         "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    'ajax': {
            type : 'POST',
            url : "{{ route('user.get_projects') }}",
            'data': function(data){

                var token = "{{ csrf_token() }}";
                data._token = token;
            }
    },
    columns: [
        {data: 'DT_RowIndex'},
        {data: 'project_name'},
        {data: 'project_location'},
        {data: 'client_name'},
        {data: 'engineer_name'},
        {data: 'period'},
        {data: 'boq_items'},
        {data: 'budget'},
        {data: 'progress'},
        {data: 'status'},
        {data: 'action',orderable: false, searchable: false}
    ]
});

function project_status(project_id)
{
	var csrf = "{{ csrf_token() }}";

	$.ajax({
	    url: "{{ route('user.project_status') }}",
	    type: "post",
	    data: "project_id="+project_id+'&_token='+csrf,
	    success: function (data) {

         $('#statusModal').modal('show');
         $("#project_status_form").html(data);
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
            window.location.href = urlToRedirect;
        }
    });
}
</script>
@endsection
