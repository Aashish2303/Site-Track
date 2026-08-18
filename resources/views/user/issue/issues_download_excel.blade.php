<table class="table table-bordered">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Date</th>
            <th>Project</th>
            <th>Title</th>
            <th>Description</th>
            <th>Category</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Resolution Remarks</th>
            <th>Assigned To</th>
            <th>Remarks</th>
            <th>Raised By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($issues as $issue)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date('d M Y',strtotime($issue->issue_date)) }}</td>
            <td>{{ $issue->project->project_name }}</td>
            <td>{{ $issue->issue_title }}</td>
            <td>{{ $issue->issue_description }}</td>
            <td>{{ $issue->category->icategory_name }}</td>
            @if($issue->issue_priority == 1)
            <td>Low</td>
            @elseif($issue->issue_priority == 2)
            <td>Medium</td>
            @elseif($issue->issue_priority == 3)
            <td>High</td>
            @elseif($issue->issue_priority == 4)
            <td>Critical</td>
            @endif
            @if($issue->issue_status == 1)
            <td>Open</td>
            @elseif($issue->issue_status == 2)
            <td>In Progress</td>
            @elseif($issue->issue_status == 3)
            <td>Resolved</td>
            @elseif($issue->issue_status == 4)
            <td>Closed</td>
            @endif
            <td>{{ $issue->issue_sremarks }}</td>
            <td>{{ $issue->engineer->user_name }}</td>
            <td>{{ $issue->issue_remarks }}</td>
            <td>{{ $issue->raise->user_name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
