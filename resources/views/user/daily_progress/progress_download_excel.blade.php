<table class="table table-bordered">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Date</th>
            <th>Project</th>
            <th>Category</th>
            <th>Description</th>
            <th>BOQ</th>
            <th>Quantity</th>
            <th>Location</th>
            <th>Subcontractor</th>
            <th>Site Incharge</th>
            <th>Remarks</th>
            <th>Carry Forward</th>
            <th>% Done</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($all_progress as $progress)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date('d M Y',strtotime($progress->dprogress_date)) }}</td>
            <td>{{ $progress->project->project_name }}</td>
            <td>{{ $progress->category->category_name }}</td>
            <td>{{ $progress->dprogress_description }}</td>
            <td>{{ $progress->boq->pboq_item }}</td>
            <td>{{ $progress->dprogress_qty }}</td>
            <td>{{ $progress->dprogress_location }}</td>
            <td>{{ $progress->subcontractor->subcontractor_name }}</td>
            <td>{{ $progress->incharge->user_name }}</td>
            <td>{{ $progress->dprogress_remarks }}</td>
            @if($progress->dprogress_cforward == 1)
            <td>Yes</td>
            @elseif($progress->dprogress_cforward == 2)
            <td>No</td>
            @endif
            <td>{{ $progress->dprogress_pdone }}</td>
            @if($progress->dprogress_status == 1)
            <td>Completed</td>
            @elseif($progress->dprogress_status == 2)
            <td>Partial</td>
            @elseif($progress->dprogress_status == 3)
            <td>Delayed - Carry Forward</td>
            @elseif($progress->dprogress_status == 4)
            <td>Cancelled</td>
            @elseif($progress->dprogress_status == 5)
            <td>Not Started</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
