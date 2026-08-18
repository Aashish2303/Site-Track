<table class="table table-bordered">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Date</th>
            <th>Project</th>
            <th>Material</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Urgency</th>
            <th>Engineer</th>
            <th>Status</th>
            <th>Notes</th>
            <th>Note For Approval</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date('d M Y',strtotime($item->indent->indent_date)) }}</td>
            <td>{{ $item->indent->project->project_name }}</td>
            <td>{{ $item->material->material_name }}</td>
            <td>{{ $item->iitem_qty }}</td>
            <td>{{ $item->unit->unit_name }}</td>
            @if($item->indent->indent_urgency == 1)
            <td>Normal</td>
            @elseif($item->indent->indent_urgency == 2)
            <td>High</td>
            @elseif($item->indent->indent_urgency == 3)
            <td>Critical</td>
            @endif
            <td>{{ $item->indent->engineer->user_name }}</td>
            @if($item->indent->indent_status == 1)
            <td>Pending Quotation</td>
            @elseif($item->indent->indent_status == 2)
            <td>MD Review</td>
            @elseif($item->indent->indent_status == 3)
            <td>Approved</td>
            @elseif($item->indent->indent_status == 4)
            <td>Rejected</td>
            @endif
            <td>{{ $item->indent->indent_notes }}</td>
            <td>{{ $item->indent->indent_napproval }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
