@extends('user.layouts.app')
@section('title', 'Clients - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Clients</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">Clients</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.clients') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         @if(Helper::userHasPermission('user.add_client'))
         <a href="{{ route('user.add_client') }}" class="btn btn-primary"><i class="ti ti-square-rounded-plus me-1"></i>Add Client</a>
         @endif
      </div>
   </div>
   <div class="card mb-0">
      <div class="card-header d-flex align-items-center flex-wrap gap-2 justify-content-between">
         <h5 class="d-inline-flex align-items-center mb-0">Manage Clients</h5>
      </div>
      <div class="card-body">
         <div class="table-responsive-sm">
            <table id="client_table" class="table border mb-0 w-100">
               <thead class="table-light">
                  <tr>
                     <th>#</th>
                     <th>Client</th>
                     <th>Email</th>
                     <th>Phone</th>
                     <th>Address</th>
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
@endsection
@section('custom_script')
<script nonce="{{ app(\App\Security\SecurityPolicy::class)->nonce() }}">
var dataTable = $('#client_table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    autoWidth: false,
    dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
         "t" +
         "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    'ajax': {
            type : 'POST',
            url : "{{ route('user.get_clients') }}",
            'data': function(data){

                var token = "{{ csrf_token() }}";
                data._token = token;
            }
    },
    columns: [
        {data: 'DT_RowIndex'},
        {data: 'client_name'},
        {data: 'client_email'},
        {data: 'client_phone'},
        {data: 'client_address'},
        {data: 'action',orderable: false, searchable: false}
    ]
});

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
