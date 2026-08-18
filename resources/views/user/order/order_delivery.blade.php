@extends('user.layouts.app')
@section('title', 'Order Delivery - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Order Delivery</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.orders') }}">Orders</a></li>
               <li class="breadcrumb-item active">Order Delivery</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.order_delivery',['id'=>$order->order_id]) }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.orders') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   @if($total_delivery < $order->order_qty)
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="order_delivery_form" action="{{ route('user.add_order_delivery',['id'=>$order->order_id]) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="material_id" value="{{ $order->material->material_id }}">
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Order Delivery</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-2">
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="odelivery_date" class="form-control" value="{{ date('Y-m-d') }}" autocomplete="off">
                                 @if($errors->has('odelivery_date'))
                                 <div>{{ $errors->first('odelivery_date') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Quantity<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="odelivery_qty" class="form-control" value="{{ old('odelivery_qty') }}" autocomplete="off">
                                 @if($errors->has('odelivery_qty'))
                                 <div>{{ $errors->first('odelivery_qty') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">DC (Delivery Challan) Number<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="odelivery_dc" class="form-control" value="{{ old('odelivery_dc') }}" autocomplete="off">
                                 @if($errors->has('odelivery_dc'))
                                 <div>{{ $errors->first('odelivery_dc') }}</div>
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
   @if($deliveries->count() > 0)
   <div class="col-xl-12 d-flex mt-4">
      <div class="card shadow flex-fill w-100">
         <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
               <h5 class="mb-0">Deliveries</h5>
         </div>
         <div class="card-body">
            <div class="table-responsive table-nowrap">
               <table class="table border mb-0">
                  <thead class="table-light">
                     <tr>
                        <th>Date</th>
                        <th>Quantity</th>
                        <th>DC (Delivery Challan) Number</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($deliveries as $delivery)
                     <tr>
                        <td>{{ date('d M Y',strtotime($delivery->odelivery_date)) }}</td>
                        <td>{{ $delivery->odelivery_qty }}</td>
                        <td>{{ $delivery->odelivery_dc }}</td>
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