<table class="table table-bordered">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Date</th>
            <th>Vendor</th>
            <th>Project</th>
            <th>Type</th>
            <th>PO Number</th>
            <th>Description</th>
            <th>PO Amount (₹)</th>
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
            <td>{{ date('d M Y',strtotime($ledger->vledger_date)) }}</td>
            <td>{{ $ledger->vendor->vendor_company }}</td>
            <td>{{ $ledger->project->project_name }}</td>
            @if($ledger->vledger_type == 1)
            <td>Purchase</td>
            @elseif($ledger->vledger_type == 2)
            <td>Advance</td>
            @elseif($ledger->vledger_type == 3)
            <td>Return</td>
            @elseif($ledger->vledger_type == 4)
            <td>Debit Note</td>
            @elseif($ledger->vledger_type == 5)
            <td>Credit Note</td>
            @endif
            <td>{{ $ledger->vledger_pono }}</td>
            <td>{{ $ledger->vledger_description }}</td>
            <td>{{ $ledger->vledger_po_total }}</td>
            <td>{{ $ledger->vledger_invoice }}</td>
            <td>{{ $ledger->vledger_paid }}</td>
            <td>{{ $ledger->vledger_po_total - $ledger->vledger_paid }}</td>
            <td>{{ $ledger->vledger_remarks }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
