@extends('user.layouts.app')
@section('title', 'Indent Items - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Indent Items</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.indents') }}">Material Indents</a></li>
               <li class="breadcrumb-item active">Indent Items</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.indent_items',['id'=>$indent->indent_id]) }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.indents') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="col-xl-12 d-flex mt-4">
      <div class="card shadow flex-fill w-100">
         <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
               <h5 class="mb-0">Items</h5>
         </div>
         <div class="card-body">
            <div class="table-responsive table-nowrap">
               <table class="table border mb-0">
                  <thead class="table-light">
                        <tr>
                           <th>Material</th>
                           <th>Category</th>
                           <th>Size</th>
                           <th>Quantity</th>
                           <th>Unit</th>
                           <th>Status</th>
                           @if(Helper::userHasPermission('user.indent_item_status'))
                           <th>Action</th>
                           @endif
                        </tr>
                  </thead>
                  <tbody>
                     @foreach($items as $item)
                     <tr>
                        <td>{{ $item->material->material_name }}</td>
                        <td>{{ $item->category->category_name }}</td>
                        <td>{{ $item->iitem_size }}</td>
                        <td>{{ $item->iitem_qty }}</td>
                        <td>{{ $item->unit->unit_name }}</td>
                        <td>
                           @if($item->iitem_status == 1)
                           <span class="badge badge-soft-info">Pending</span>
                           @elseif($item->iitem_status == 2)
                           <span class="badge badge-soft-success">Approved</span>
                           @elseif($item->iitem_status == 3)
                           <span class="badge badge-soft-danger">Rejected</span>
                           @elseif($item->iitem_status == 4)
                           <span class="badge badge-soft-warning">On Hold</span>
                           @endif
                        </td>
                        @if(Helper::userHasPermission('user.indent_item_status'))
                        <td>
                           <div class="d-flex align-items-center gap-2">
                             <a href="#" class="btn btn-icon btn-sm btn-dark" data-st-click="indent_item_status({{ $item->iitem_id }})" title="Status"><i class="ti ti-check"></i></a>
                           </div>
                        </td>
                        @endif
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
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
            <form id="indent_item_status_form" action="{{ route('user.add_indent_item_status',['id'=>$indent->indent_id]) }}" method="POST">
            </form>
      </div>
   </div>
</div>
@endsection
@section('custom_script')
<script nonce="{{ app(\App\Security\SecurityPolicy::class)->nonce() }}">
function indent_item_status(item_id)
{
	var csrf = "{{ csrf_token() }}";

	$.ajax({
	    url: "{{ route('user.indent_item_status') }}",
	    type: "post",
	    data: "item_id="+item_id+'&_token='+csrf,
	    success: function (data) {

         $('#statusModal').modal('show');
         $("#indent_item_status_form").html(data);
	    }
	});
}
</script>
@endsection
