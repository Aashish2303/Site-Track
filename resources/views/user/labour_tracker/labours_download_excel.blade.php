<table class="table table-bordered">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Date</th>
            <th>Project</th>
            <th>Subcontractor</th>
            <th>Category</th>
            <th>Skill</th>
            <th>Male</th>
            <th>Female</th>
            <th>Total</th>
            <th>In</th>
            <th>Out</th>
            <th>Hours</th>
            <th>Wage</th>
            <th>Cost</th>
            <th>Shift</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($workers as $worker)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date('d M Y',strtotime($worker->ltracker_date)) }}</td>
            <td>{{ $worker->project_name }}</td>
            <td>{{ $worker->subcontractor_name }}</td>
            <td>{{ $worker->category_name }}</td>
            @if($worker->lworker_skill == 1)
            <td>Skilled</td>
            @elseif($worker->lworker_skill == 2)
            <td>Unskilled</td>
            @elseif($worker->lworker_skill == 3)
            <td>Semi Skilled</td>
            @endif
            <td>{{ $worker->lworker_male }}</td>
            <td>{{ $worker->lworker_female }}</td>
            <td>{{ $worker->lworker_male + $worker->lworker_female }}</td>
            <td>{{ $worker->ltracker_in }}</td>
            <td>{{ $worker->ltracker_out }}</td>
            <td>{{ $worker->ltracker_thours }}</td>
            <td>{{ $worker->lworker_wage }}</td>
            <td>{{ $worker->lworker_total }}</td>
            @if($worker->ltracker_shift == 1)
            <td>Day</td>
            @elseif($worker->ltracker_shift == 2)
            <td>Night</td>
            @endif
            <td>{{ $worker->ltracker_remarks }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
