<table class="table table-bordered">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Date</th>
            <th>Project</th>
            <th>Category</th>
            <th>Description</th>
            <th>Location</th>
            <th>Quantity</th>
            <th>Subcontractor</th>
            <th>Site Incharge</th>
            <th>Remarks</th>
            <th>Carry Forward</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($daily_works as $daily_work)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date('d M Y',strtotime($daily_work->dwork_date)) }}</td>
            <td>{{ $daily_work->project->project_name }}</td>
            <td>{{ $daily_work->category->category_name }}</td>
            <td>{{ $daily_work->dwork_description }}</td>
            <td>{{ $daily_work->dwork_location }}</td>
            <td>{{ $daily_work->dwork_qty }}</td>
            <td>{{ $daily_work->subcontractor->subcontractor_name }}</td>
            <td>{{ $daily_work->incharge->user_name }}</td>
            <td>{{ $daily_work->dwork_remarks }}</td>
            @if($daily_work->dwork_cforward == 1)
            <td>Yes</td>
            @elseif($daily_work->dwork_cforward == 2)
            <td>No</td>
            @endif
            @if($daily_work->dwork_status == 1)
            <td>Completed</td>
            @elseif($daily_work->dwork_status == 2)
            <td>Partial</td>
            @elseif($daily_work->dwork_status == 3)
            <td>Delayed - Carry Forward</td>
            @elseif($daily_work->dwork_status == 4)
            <td>Cancelled</td>
            @elseif($daily_work->dwork_status == 5)
            <td>Not Started</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
