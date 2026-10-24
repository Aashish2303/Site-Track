@extends('user.layouts.app')
@section('title', 'Add Order - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Add Order</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.orders') }}">Orders</a></li>
               <li class="breadcrumb-item active">Add Order</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.add_order') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.orders') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="order_form" action="{{ route('user.add_order') }}" method="POST">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Order</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" autocomplete="off">
                                 @if($errors->has('order_date'))
                                 <div>{{ $errors->first('order_date') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">PO Number<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="order_po_no" class="form-control" value="{{ old('order_po_no') }}" autocomplete="off">
                                 @if($errors->has('order_po_no'))
                                 <div>{{ $errors->first('order_po_no') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Vendor<span class="text-danger ms-1">*</span></label>
                                 <select name="order_vendor" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_company }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('order_vendor'))
                                 <div>{{ $errors->first('order_vendor') }}</div>
                                 @endif
                                 <div id="order_vendor_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Material<span class="text-danger ms-1">*</span></label>
                                 <select name="order_material" id="order_material" class="form-control select2" data-toggle="select2" data-st-change="select_material_unit()">
                                    <option value="">Select</option>
                                    @foreach($materials as $material)
                                    <option value="{{ $material->material_id }}">{{ $material->material_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('order_material'))
                                 <div>{{ $errors->first('order_material') }}</div>
                                 @endif
                                 <div id="order_material_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Unit<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="order_unit_name" id="order_unit_name" class="form-control" value="{{ old('order_unit_name') }}" autocomplete="off" readonly>
                                 <input type="hidden" name="order_unit" id="order_unit" class="form-control" value="">
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Quantity<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="order_qty" class="form-control" value="{{ old('order_qty') }}" autocomplete="off">
                                 @if($errors->has('order_qty'))
                                 <div>{{ $errors->first('order_qty') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Rate (₹)<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="order_rate" class="form-control" value="{{ old('order_rate') }}" autocomplete="off">
                                 @if($errors->has('order_rate'))
                                 <div>{{ $errors->first('order_rate') }}</div>
                                 @endif
                              </div>
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
<script nonce="{{ app(\App\Security\SecurityPolicy::class)->nonce() }}">
function select_material_unit()
{
   var material_id = $('#order_material').val();

   $.ajax({
      url: "{{ route('user.select_material_unit') }}",
      type: "POST",
      data: {
            _token: "{{ csrf_token() }}",
            material_id: material_id
      },
      success: function(response)
      {
         $('#order_unit_name').val(response.unit_name);
         $('#order_unit').val(response.unit_id);
      }
   });
}
</script>
@endsection
