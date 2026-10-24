@extends('user.layouts.app')
@section('title', 'Labour Tracker - ' . Request()->settings->settings_title)
@section('custom_style')
<style>
.labour-summary-card {
   margin-bottom: 1rem;
}

.labour-summary-card .card-body {
   padding: 1rem 1.25rem;
}
</style>
@endsection
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Labour Tracker</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">Labour Tracker</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.labour_trackers') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         @if(Helper::userHasPermission('user.labours_download_excel'))
         <a href="{{ route('user.labours_download_excel',['date'=>Request()->date,'project'=>Request()->project]) }}" id="excel_download" class="btn btn-success"><i class="ti ti-download me-1"></i>Download</a>
         @endif
         @if(Helper::userHasPermission('user.add_labour_tracker'))
         <a href="{{ route('user.add_labour_tracker') }}" class="btn btn-primary"><i class="ti ti-square-rounded-plus me-1"></i>Add Labour Tracker</a>
         @endif
      </div>
   </div>
   <div class="row">
      <div class="col-md-2">
         <div class="mb-3">
            <input type="date" name="date" id="date" class="form-control" @if(Request()->has('date')) value="{{ Request()->date }}" @else value="{{ date('Y-m-d') }}" @endif onchange="select_filter()">
         </div>
      </div>
      <div class="col-md-2">
         <select name="project" id="project" class="form-control select2" data-toggle="select2" onchange="select_filter()">
            <option value="">All Project</option>
            @foreach($projects as $project)
            <option value="{{ $project->project_id }}" {{ Request()->project == $project->project_id ? 'selected':'' }}>{{ $project->project_name }}</option>
            @endforeach
         </select>
      </div>
   </div>
   <div class="row">
      <div class="col-xl-3 col-md-6 d-flex">
         <div class="card flex-fill labour-summary-card">
            <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                     <div>
                        <p class="mb-1">Total Workers</p>
                        <h6 class="mb-0">{{ $total_workers }}</h6>
                     </div>
                     <span class="avatar rounded-circle badge-soft-primary"><i class="ti ti-users-group fs-24"></i></span>
                  </div>
            </div>
         </div>
      </div>
      <div class="col-xl-3 col-md-6 d-flex">
         <div class="card flex-fill labour-summary-card">
            <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                     <div>
                        <p class="mb-1">Total Male</p>
                        <h6 class="mb-0">{{ $total_male }}</h6>
                     </div>
                     <span class="avatar rounded-circle badge-soft-pink"><i class="ti ti-user fs-24"></i></span>
                  </div>
            </div>
         </div>
      </div>
      <div class="col-xl-3 col-md-6 d-flex">
         <div class="card flex-fill labour-summary-card">
            <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                     <div>
                        <p class="mb-1">Total Female</p>
                        <h6 class="mb-0">{{ $total_female }}</h6>
                     </div>
                     <span class="avatar rounded-circle badge-soft-indigo"><i class="ti ti-user fs-24"></i></span>
                  </div>
            </div>
         </div>
      </div>
      <div class="col-xl-3 col-md-6 d-flex">
         <div class="card flex-fill labour-summary-card">
            <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                     <div>
                        <p class="mb-1">Total Cost</p>
                        <h6 class="mb-0">{{ Helper::moneyFormat($total_cost) }}</h6>
                     </div>
                     <span class="avatar rounded-circle badge-soft-orange"><i class="ti ti-cards fs-24"></i></span>
                  </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-3">
         <div class="card mb-0">
            <div class="card-header d-flex align-items-center flex-wrap gap-2 justify-content-between">
               <h5 class="d-inline-flex align-items-center mb-0">Trade Breakdown</h5>
            </div>
            <div class="card-body">
               <div class="table-responsive-sm">
                  <table class="table border mb-0 w-100">
                     <thead class="table-light">
                        <tr>
                           <th>Category</th>
                           <th>Total</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($trades as $category_name => $trade)
                        <tr>
                           <td>{{ $category_name }}</td>
                           <td>{{ array_sum($trade) }}</td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-9">
         <div class="card mb-0">
            <div class="card-header d-flex align-items-center flex-wrap gap-2 justify-content-between">
               <h5 class="d-inline-flex align-items-center mb-0">Manage Labour Tracker</h5>
            </div>
            <div class="card-body">
               <div class="table-responsive-sm">
                  <table id="labour_tracker_table" class="table border mb-0 w-100">
                     <thead class="table-light">
                        <tr>
                           <th>#</th>
                           <th>Date</th>
                           <th>Project</th>
                           <th>Categories</th>
                           <th>M/F</th>
                           <th>Time</th>
                           <th>Total Hours</th>
                           <th>Shift</th>
                           <th>Cost (₹)</th>
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
   </div>
</div>
@endsection
@section('custom_script')
<script>
var dataTable = $('#labour_tracker_table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    autoWidth: false,
    dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
         "t" +
         "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    'ajax': {
            type : 'POST',
            url : "{{ route('user.get_labour_trackers') }}",
            'data': function(data){

               var token = "{{ csrf_token() }}";
               var date = "{{ Request()->date }}";
               var project = "{{ Request()->project }}";

               data._token = token;
               data.date = date;
               data.project = project;
            }
    },
    columns: [
        {data: 'DT_RowIndex'},
        {data: 'tracker_date'},
        {data: 'project_name'},
        {data: 'categories'},
        {data: 'mf'},
        {data: 'time_period'},
        {data: 'ltracker_thours'},
        {data: 'shift'},
        {data: 'total_cost'},
        {data: 'action',orderable: false, searchable: false}
    ]
});

function select_filter()
{
   var date = $("#date").val();
   var project = $("#project").val();

   window.location.href = '/user/labour_trackers?date='+date+'&project='+project;
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
