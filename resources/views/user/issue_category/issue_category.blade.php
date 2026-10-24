@extends('user.layouts.app')
@section('title', 'Issue Category - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Issue Category</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">Issue Category</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.issue_category') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         @if(Helper::userHasPermission('user.add_issue_category'))
         <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addIssueCategoryModal"><i class="ti ti-square-rounded-plus me-1"></i>Add Category</a>
         @endif
      </div>
   </div>
   <div class="card mb-0">
      <div class="card-header d-flex align-items-center flex-wrap gap-2 justify-content-between">
         <h5 class="d-inline-flex align-items-center mb-0">Manage Issue Category</h5>
      </div>
      <div class="card-body">
         <div class="table-responsive-sm">
            <table id="issue_category_table" class="table border mb-0 w-100">
               <thead class="table-light">
                  <tr>
                     <th>#</th>
                     <th>Category</th>
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
<div id="addIssueCategoryModal" class="modal fade">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
            <div class="modal-header">
               <h5 class="text-dark modal-title fw-bold text-truncate">Add Category</h5>
               <button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-circle-x-filled"></i></button>
            </div>
            <form id="issue_category_form" action="{{ route('user.add_issue_category') }}" method="POST">
               @csrf
               <div class="modal-body pb-0">
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="mb-3">
                           <label class="form-label">Category</label>
                           <input type="text" name="icategory_name" class="form-control" id="choices-text-remove-button" value="{{ old('icategory_name') }}" autocomplete="off">
                           @if($errors->has('icategory_name'))
                           <div>{{ $errors->first('icategory_name') }}</div>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer d-flex align-items-center gap-1">
                  <button type="submit" name="submit" class="btn btn-primary" value="submit">Submit</button>
               </div>
            </form>
      </div>
   </div>
</div>
<div id="editIssueCategoryModal" class="modal fade">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
            <div class="modal-header">
               <h5 class="text-dark modal-title fw-bold text-truncate">Edit Category</h5>
               <button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-circle-x-filled"></i></button>
            </div>
            <form id="edit_issue_category_form" action="{{ route('user.edit_issue_category') }}" method="POST">
            </form>
      </div>
   </div>
</div>
@endsection
@section('custom_script')
<script>
var dataTable = $('#issue_category_table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    autoWidth: false,
    dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
         "t" +
         "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    'ajax': {
            type : 'POST',
            url : "{{ route('user.get_issue_category') }}",
            'data': function(data){

                var token = "{{ csrf_token() }}";
                data._token = token;
            }
    },
    columns: [
        {data: 'DT_RowIndex'},
        {data: 'icategory_name'},
        {data: 'action',orderable: false, searchable: false}
    ]
});

function edit_issue_category(icategory_id)
{
    var csrf = "{{ csrf_token() }}";

    $.ajax({
        url: "{{ route('user.get_issue_category_details') }}",
        type: "post",
        data: "icategory_id="+icategory_id+'&_token='+csrf,
        success: function (data) {

            $('#editIssueCategoryModal').modal('show');
            $("#edit_issue_category_form").html(data);
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
