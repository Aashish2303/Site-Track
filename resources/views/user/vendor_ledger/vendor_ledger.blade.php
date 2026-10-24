@extends('user.layouts.app')
@section('title', 'Vendor Ledger - ' . Request()->settings->settings_title)
@section('custom_style')
<style>
.vendorledger-summary-card {
   overflow: hidden;
   border-top: 3px solid #ff9f1a;
}

.vendorledger-summary-card.is-clear {
   border-top-color: #00b884;
}

.vendorledger-summary-card.is-due {
   border-top-color: #ff4d57;
}

.vendorledger-summary-card .card-body {
   padding: 1rem 1.25rem;
}

.vendorledger-summary-title {
   font-size: 1rem;
   font-weight: 700;
   color: var(--gray-900);
   margin-bottom: .1rem;
}

.vendorledger-summary-meta {
   color: var(--body-color);
   font-size: .8125rem;
}

.vendorledger-summary-grid {
   display: grid;
   grid-template-columns: repeat(4, minmax(0, 1fr));
   gap: .55rem;
   margin-top: .875rem;
}

.vendorledger-summary-label {
   display: block;
   color: var(--body-color);
   font-size: .625rem;
   font-weight: 700;
   letter-spacing: .04em;
   line-height: 1.1;
   text-transform: uppercase;
}

.vendorledger-summary-value {
   display: block;
   font-size: .875rem;
   font-weight: 800;
   line-height: 1.2;
}

.vendorledger-summary-value.po,
.vendorledger-summary-value.invoice {
   color: #ff7a1a;
}

.vendorledger-summary-value.paid {
   color: #00b884;
}

.vendorledger-summary-value.balance {
   color: #ff4d57;
}

.vendorledger-summary-card.is-clear .vendorledger-summary-value.balance {
   color: #00b884;
}

.vendorledger-summary-progress {
   height: 6px;
   overflow: hidden;
   border-radius: 999px;
   background: #e9edf5;
   margin-top: .875rem;
}

.vendorledger-summary-progress span {
   display: block;
   width: var(--vendorledger-paid, 0%);
   height: 100%;
   border-radius: inherit;
   background: var(--vendorledger-progress-color, #ff9f1a);
}

.vendorledger-summary-percent {
   color: var(--body-color);
   font-size: .75rem;
   text-align: right;
   margin-top: .35rem;
}
</style>
@endsection
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Vendor Ledger</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">Vendor Ledger</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.vendor_ledger') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         @if(Helper::userHasPermission('user.vendor_ledger_download_excel'))
         <a href="{{ route('user.vendor_ledger_download_excel') }}" id="excel_download" class="btn btn-success"><i class="ti ti-download me-1"></i>Download</a>
         @endif
         @if(Helper::userHasPermission('user.add_vendor_ledger'))
         <a href="{{ route('user.add_vendor_ledger') }}" class="btn btn-primary"><i class="ti ti-square-rounded-plus me-1"></i>Add Vendor Ledger</a>
         @endif
      </div>
   </div>
   @if($ledger_summaries->count() > 0)
   <div class="row">
      @foreach($ledger_summaries as $summary)
      @php
         $summaryClass = $summary['balance'] <= 0 ? 'is-clear' : ($summary['paid_percent'] < 50 ? 'is-due' : '');
         $progressColor = $summary['balance'] <= 0 ? '#00b884' : ($summary['paid_percent'] < 50 ? '#ff4d57' : '#ff9f1a');
      @endphp
      <div class="col-xl-3 col-md-6 d-flex">
         <div class="card flex-fill vendorledger-summary-card {{ $summaryClass }}">
            <div class="card-body">
               <h5 class="vendorledger-summary-title">{{ $summary['vendor_name'] ?? 'Vendor' }}</h5>
               <div class="vendorledger-summary-meta">{{ $summary['entries'] }} {{ $summary['entries'] == 1 ? 'entry' : 'entries' }}</div>
               <div class="vendorledger-summary-grid">
                  <div>
                     <span class="vendorledger-summary-label">PO Val</span>
                     <span class="vendorledger-summary-value po">&#8377;{{ Helper::moneyFormat($summary['po_total']) }}</span>
                  </div>
                  <div>
                     <span class="vendorledger-summary-label">Invoice</span>
                     <span class="vendorledger-summary-value invoice">&#8377;{{ Helper::moneyFormat($summary['invoice']) }}</span>
                  </div>
                  <div>
                     <span class="vendorledger-summary-label">Paid</span>
                     <span class="vendorledger-summary-value paid">&#8377;{{ Helper::moneyFormat($summary['paid']) }}</span>
                  </div>
                  <div>
                     <span class="vendorledger-summary-label">Balance</span>
                     <span class="vendorledger-summary-value balance">&#8377;{{ Helper::moneyFormat($summary['balance']) }}</span>
                  </div>
               </div>
               <div class="vendorledger-summary-progress" style="--vendorledger-paid: {{ $summary['paid_percent'] }}%; --vendorledger-progress-color: {{ $progressColor }};">
                  <span></span>
               </div>
               <div class="vendorledger-summary-percent">{{ $summary['paid_percent'] }}% paid</div>
            </div>
         </div>
      </div>
      @endforeach
   </div>
   @endif
   <div class="card mb-0">
      <div class="card-header d-flex align-items-center flex-wrap gap-2 justify-content-between">
         <h5 class="d-inline-flex align-items-center mb-0">Manage Vendor Ledger</h5>
      </div>
      <div class="card-body">
         <div class="table-responsive-sm">
            <table id="vendor_ledger_table" class="table border mb-0 w-100">
               <thead class="table-light">
                  <tr>
                     <th>#</th>
                     <th>Date</th>
                     <th>Vendor</th>
                     <th>Project</th>
                     <th>Type</th>
                     <th>Po Number</th>
                     <th>Description</th>
                     <th>PO Amount (₹)</th>
                     <th>Invoice Amount (₹)</th>
                     <th>Paid Amount (₹)</th>
                     <th>Balance (₹)</th>
                     <th>Remarks</th>
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
<script>
var dataTable = $('#vendor_ledger_table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    autoWidth: false,
    dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
         "t" +
         "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    'ajax': {
            type : 'POST',
            url : "{{ route('user.get_vendor_ledger') }}",
            'data': function(data){

                var token = "{{ csrf_token() }}";
                data._token = token;
            }
    },
    columns: [
        {data: 'DT_RowIndex'},
        {data: 'ledger_date'},
        {data: 'vendor_name'},
        {data: 'project_name'},
        {data: 'type_name'},
        {data: 'vledger_pono'},
        {data: 'vledger_description'},
        {data: 'vledger_po_total'},
        {data: 'vledger_invoice'},
        {data: 'vledger_paid'},
        {data: 'balance'},
        {data: 'vledger_remarks'}
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
