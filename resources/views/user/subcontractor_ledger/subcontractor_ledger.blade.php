@extends('user.layouts.app')
@section('title', 'Subcontractor Ledger - ' . Request()->settings->settings_title)
@section('custom_style')
<style>
.subledger-summary-card {
   overflow: hidden;
   border-top: 3px solid #ff9f1a;
}

.subledger-summary-card.is-clear {
   border-top-color: #00b884;
}

.subledger-summary-card.is-due {
   border-top-color: #ff4d57;
}

.subledger-summary-card .card-body {
   padding: 1rem 1.25rem;
}

.subledger-summary-title {
   font-size: 1rem;
   font-weight: 700;
   color: var(--gray-900);
   margin-bottom: .1rem;
}

.subledger-summary-meta {
   color: var(--body-color);
   font-size: .8125rem;
}

.subledger-summary-grid {
   display: grid;
   grid-template-columns: repeat(4, minmax(0, 1fr));
   gap: .55rem;
   margin-top: .875rem;
}

.subledger-summary-label {
   display: block;
   color: var(--body-color);
   font-size: .625rem;
   font-weight: 700;
   letter-spacing: .04em;
   line-height: 1.1;
   text-transform: uppercase;
}

.subledger-summary-value {
   display: block;
   font-size: .875rem;
   font-weight: 800;
   line-height: 1.2;
}

.subledger-summary-value.total,
.subledger-summary-value.invoice {
   color: #ff7a1a;
}

.subledger-summary-value.paid {
   color: #00b884;
}

.subledger-summary-value.balance {
   color: #ff4d57;
}

.subledger-summary-card.is-clear .subledger-summary-value.balance {
   color: #00b884;
}

.subledger-summary-progress {
   height: 6px;
   overflow: hidden;
   border-radius: 999px;
   background: #e9edf5;
   margin-top: .875rem;
}

.subledger-summary-progress span {
   display: block;
   width: var(--subledger-paid, 0%);
   height: 100%;
   border-radius: inherit;
   background: var(--subledger-progress-color, #ff9f1a);
}

.subledger-summary-percent {
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
         <h4 class="mb-1">Subcontractor Ledger</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">Subcontractor Ledger</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.subcontractor_ledger') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         @if(Helper::userHasPermission('user.subcontractor_ledger_download_excel'))
         <a href="{{ route('user.subcontractor_ledger_download_excel') }}" id="excel_download" class="btn btn-success"><i class="ti ti-download me-1"></i>Download</a>
         @endif
         @if(Helper::userHasPermission('user.add_subcontractor_ledger'))
         <a href="{{ route('user.add_subcontractor_ledger') }}" class="btn btn-primary"><i class="ti ti-square-rounded-plus me-1"></i>Add Subcontractor Ledger</a>
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
         <div class="card flex-fill subledger-summary-card {{ $summaryClass }}">
            <div class="card-body">
               <h5 class="subledger-summary-title">{{ $summary['subcontractor_name'] ?? 'Subcontractor' }}</h5>
               <div class="subledger-summary-meta">{{ $summary['entries'] }} {{ $summary['entries'] == 1 ? 'entry' : 'entries' }}</div>
               <div class="subledger-summary-grid">
                  <div>
                     <span class="subledger-summary-label">Total WO</span>
                     <span class="subledger-summary-value total">&#8377;{{ Helper::moneyFormat($summary['total']) }}</span>
                  </div>
                  <div>
                     <span class="subledger-summary-label">Invoice</span>
                     <span class="subledger-summary-value invoice">&#8377;{{ Helper::moneyFormat($summary['invoice']) }}</span>
                  </div>
                  <div>
                     <span class="subledger-summary-label">Paid</span>
                     <span class="subledger-summary-value paid">&#8377;{{ Helper::moneyFormat($summary['paid']) }}</span>
                  </div>
                  <div>
                     <span class="subledger-summary-label">Balance</span>
                     <span class="subledger-summary-value balance">&#8377;{{ Helper::moneyFormat($summary['balance']) }}</span>
                  </div>
               </div>
               <div class="subledger-summary-progress" style="--subledger-paid: {{ $summary['paid_percent'] }}%; --subledger-progress-color: {{ $progressColor }};">
                  <span></span>
               </div>
               <div class="subledger-summary-percent">{{ $summary['paid_percent'] }}% paid</div>
            </div>
         </div>
      </div>
      @endforeach
   </div>
   @endif
   <div class="card mb-0">
      <div class="card-header d-flex align-items-center flex-wrap gap-2 justify-content-between">
         <h5 class="d-inline-flex align-items-center mb-0">Manage Subcontractor Ledger</h5>
      </div>
      <div class="card-body">
         <div class="table-responsive-sm">
            <table id="subcontractor_ledger_table" class="table border mb-0 w-100">
               <thead class="table-light">
                  <tr>
                     <th>#</th>
                     <th>Date</th>
                     <th>Subcontractor</th>
                     <th>Project</th>
                     <th>Type</th>
                     <th>WO / Po Number</th>
                     <th>Description</th>
                     <th>Total Amount (₹)</th>
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
var dataTable = $('#subcontractor_ledger_table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    autoWidth: false,
    dom: "<'row align-items-center mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
         "t" +
         "<'row align-items-center mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    'ajax': {
            type : 'POST',
            url : "{{ route('user.get_subcontractor_ledger') }}",
            'data': function(data){

                var token = "{{ csrf_token() }}";
                data._token = token;
            }
    },
    columns: [
        {data: 'DT_RowIndex'},
        {data: 'ledger_date'},
        {data: 'subcontractor_name'},
        {data: 'project_name'},
        {data: 'type_name'},
        {data: 'sledger_wpno'},
        {data: 'sledger_description'},
        {data: 'total_amount'},
        {data: 'sledger_invoice'},
        {data: 'paid_amount'},
        {data: 'balance'},
        {data: 'sledger_remarks'}
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
