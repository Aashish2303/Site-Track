<table class="table table-bordered">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Date</th>
            <th>Subcontractor</th>
            <th>Project</th>
            <th>Type</th>
            <th>WO / PO Number</th>
            <th>Description</th>
            <th>Total Amount (₹)</th>
            <th>Invoice Amount (₹)</th>
            <th>Paid Amount (₹)</th>
            <th>Balance (₹)</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($all_ledger as $ledger)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date('d M Y',strtotime($ledger->sledger_date)) }}</td>
            <td>{{ $ledger->subcontractor->subcontractor_name }}</td>
            <td>{{ $ledger->project->project_name }}</td>
            @if($ledger->sledger_type == 1)
            <td>Work Order</td>
            @elseif($ledger->sledger_type == 2)
            <td>Running Bill</td>
            @elseif($ledger->sledger_type == 3)
            <td>Final Bill</td>
            @elseif($ledger->sledger_type == 4)
            <td>Advance</td>
            @elseif($ledger->sledger_type == 5)
            <td>Retention Release</td>
            @elseif($ledger->sledger_type == 6)
            <td>Deduction</td>
            @endif
            <td>{{ $ledger->sledger_wpno }}</td>
            <td>{{ $ledger->sledger_description }}</td>
            <td>{{ $ledger->sledger_total }}</td>
            <td>{{ $ledger->sledger_invoice }}</td>
            <td>{{ $ledger->sledger_paid }}</td>
            <td>{{ $ledger->sledger_total - $ledger->sledger_paid }}</td>
            <td>{{ $ledger->sledger_remarks }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
