@extends('user.layouts.app')
@section('title', 'Indent Quotations - ' . Request()->settings->settings_title)
@section('custom_style')
<style>
.quotation-lowest-badge {
   display: inline-flex;
   align-items: center;
   margin-left: .5rem;
   padding: .2rem .45rem;
   border-radius: 999px;
   background-color: #ff9f1a;
   color: #fff;
   font-size: .625rem;
   font-weight: 800;
   line-height: 1;
   text-transform: uppercase;
}
</style>
@endsection
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Indent Quotations</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.indents') }}">Material Indents</a></li>
               <li class="breadcrumb-item active">Indent Quotations</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.indent_quotations',['id'=>$indent->indent_id]) }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.indents') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   @if($indent->indent_status == 1 || $indent->indent_status == 2)
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="indent_quotation_form" action="{{ route('user.add_quotation',['id'=>$indent->indent_id]) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Quotation</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-2">
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Vendor<span class="text-danger ms-1">*</span></label>
                                 <select name="iquote_vendor" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('iquote_vendor'))
                                 <div>{{ $errors->first('iquote_vendor') }}</div>
                                 @endif
                                 <div id="iquote_vendor_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Rate (₹/Unit)<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="iquote_rate" class="form-control" value="{{ old('iquote_rate') }}" autocomplete="off">
                                 @if($errors->has('iquote_rate'))
                                 <div>{{ $errors->first('iquote_rate') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Total (₹)<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="iquote_total" class="form-control" value="{{ old('iquote_total') }}" autocomplete="off">
                                 @if($errors->has('iquote_total'))
                                 <div>{{ $errors->first('iquote_total') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Delivery<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="iquote_delivery" class="form-control" value="{{ old('iquote_delivery') }}" autocomplete="off">
                                 @if($errors->has('iquote_delivery'))
                                 <div>{{ $errors->first('iquote_delivery') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Notes</label>
                                 <input type="text" name="iquote_notes" class="form-control" value="{{ old('iquote_notes') }}" autocomplete="off">
                                 @if($errors->has('iquote_notes'))
                                 <div>{{ $errors->first('iquote_notes') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Quotation</label>
                                 <input type="file" name="iquote_quotation" class="form-control">
                                 @if($errors->has('iquote_quotation'))
                                 <div>{{ $errors->first('iquote_quotation') }}</div>
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
   @endif
   @if($quotations->count() > 0)
   <div class="col-xl-12 d-flex mt-4">
      <div class="card shadow flex-fill w-100">
         <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
               <h5 class="mb-0">Quotations</h5>
               @if($indent->indent_status == 1 || $indent->indent_status == 2)
               <a href="{{ route('user.reject_quotations',['id'=>$indent->indent_id]) }}" class="btn btn-sm btn-danger flex-shrink-0" data-st-click="confirm_reject_msg(event)">Reject All</a>
               @endif
         </div>
         <div class="card-body">
            <div class="table-responsive table-nowrap">
               <table class="table border mb-0">
                  <thead class="table-light">
                        <tr>
                           <th>Vendor</th>
                           <th>Rate (₹/Unit)</th>
                           <th>Total (₹)</th>
                           <th>Delivery</th>
                           <th>Notes</th>
                           <th>Status</th>
                           <th>Quotation</th>
                           @if($indent->indent_status == 2)
                           <th>Action</th>
                           @endif
                        </tr>
                  </thead>
                  <tbody>
                     @php $lowestQuotationTotal = $quotations->min('iquote_total'); @endphp
                     @foreach($quotations as $quotation)
                     <tr @if(!is_null($lowestQuotationTotal) && (float) $quotation->iquote_total == (float) $lowestQuotationTotal) class="quotation-lowest-row" @endif>
                        <td>
                           <h6 class="fs-14 mb-0 fw-medium">
                              <a href="#">{{ $quotation->vendor->vendor_name }}</a>
                           </h6>
                        </td>
                        <td>{{ Helper::moneyFormat($quotation->iquote_rate) }}</td>
                        <td>
                           {{ Helper::moneyFormat($quotation->iquote_total) }}
                           @if(!is_null($lowestQuotationTotal) && (float) $quotation->iquote_total == (float) $lowestQuotationTotal)
                           <span class="quotation-lowest-badge">Lowest</span>
                           @endif
                        </td>
                        <td>{{ $quotation->iquote_delivery }}</td>
                        <td>
                           @if(!empty($quotation->iquote_notes))
                           {{ $quotation->iquote_notes }}
                           @else
                           -
                           @endif
                        </td>
                        <td>
                           @if($quotation->iquote_status == 1)
                           <span class="badge badge-pending-quotation">Pending</span>
                           @elseif($quotation->iquote_status == 2)
                           <span class="badge badge-soft-success">Approved</span>
                           @elseif($quotation->iquote_status == 3)
                           <span class="badge badge-soft-danger">Rejected</span>
                           @endif
                        </td>
                        <td>
                           @if(!empty($quotation->iquote_quotation))
                           <a href="{{ route('user.private_file', ['kind' => 'quotation', 'reference' => app(\App\Services\PublicReference::class)->encode('indent_quotations', $quotation->iquote_id)]) }}" class="btn btn-icon btn-light me-1" download><i class="ti ti-download"></i></a>
                           @else
                           -
                           @endif
                        </td>
                        @if($indent->indent_status == 2)
                        <td>
                           <div class="d-flex align-items-center gap-2">

                              <a href="{{ route('user.status_quotation',['id'=>$quotation->iquote_id,'status'=>2]) }}" class="btn btn-success" data-st-click="confirm_msg(event)">Approve</a>
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
   @endif
</div>
@endsection
@section('custom_script')
<script nonce="{{ app(\App\Security\SecurityPolicy::class)->nonce() }}">
function confirm_reject_msg(ev)
{
    ev.preventDefault();

    var urlToRedirect = ev.currentTarget.getAttribute('href');

    Swal.fire({
        title: "Are you sure?",
        text: "You want to reject!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, reject it!",
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

function confirm_msg(ev)
{
    ev.preventDefault();

    var urlToRedirect = ev.currentTarget.getAttribute('href');

    Swal.fire({
        title: "Are you sure?",
        text: "You want to approve!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, approve it!",
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
