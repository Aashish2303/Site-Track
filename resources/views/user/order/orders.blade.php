@extends('user.layouts.app')
@section('title', 'Orders - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Orders</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">Orders</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.orders') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         @if(Helper::userHasPermission('user.add_order'))
         <a href="{{ route('user.add_order') }}" class="btn btn-primary"><i class="ti ti-square-rounded-plus me-1"></i>Add Order</a>
         @endif
      </div>
   </div>
   <div class="card mb-0">
      <div class="card-header d-flex align-items-center flex-wrap gap-2 justify-content-between">
         <h5 class="d-inline-flex align-items-center mb-0">Manage Orders</h5>
      </div>
      <div class="card-body">
         <div class="table-responsive-sm">
            <table id="order_table" class="table border mb-0 w-100">
               <thead class="table-light">
                  <tr>
                     <th>#</th>
                     <th>Date</th>
                     <th>Vendor</th>
                     <th>Material</th>
                     <th>Unit</th>
                     <th>Quantity</th>
                     <th>Delivered</th>
                     <th>Pending</th>
                     <th>Rate (₹)</th>
                     <th>Payment Status</th>
                     <th>Delivery Status</th>
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
               <h5 class="text-dark modal-title fw-bold text-truncate">Payment Status</h5>
               <button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-circle-x-filled"></i></button>
            </div>
            <form id="order_pay_status_form" action="{{ route('user.add_order_pay_status') }}" method="POST">
            </form>
      </div>
   </div>
</div>
@endsection
@section('custom_script')
<script>
var dataTable = $('#order_table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    autoWidth: false,
    dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
         "t" +
         "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    'ajax': {
            type : 'POST',
            url : "{{ route('user.get_orders') }}",
            'data': function(data){

                var token = "{{ csrf_token() }}";
                data._token = token;
            }
    },
    columns: [
        {data: 'DT_RowIndex'},
        {data: 'order_date'},
        {data: 'vendor_name'},
        {data: 'material_name'},
        {data: 'unit_name'},
        {data: 'order_qty'},
        {data: 'delivered_qty'},
        {data: 'pending_qty'},
        {data: 'order_rate'},
        {data: 'pay_status'},
        {data: 'delivery_status'},
        {data: 'action',orderable: false, searchable: false}
    ]
});

function order_pay_status(order_id)
{
	var csrf = "{{ csrf_token() }}";

	$.ajax({
	    url: "{{ route('user.order_pay_status') }}",
	    type: "post",
	    data: "order_id="+order_id+'&_token='+csrf,
	    success: function (data) {

         $('#statusModal').modal('show');
         $("#order_pay_status_form").html(data);
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
