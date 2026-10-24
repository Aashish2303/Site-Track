@php
    $record = $labour_tracker ?? null;
    $overtimeEnabled = app(\App\Services\WorkflowSchema::class)->ready();
    $initial = [];
    foreach(($workers ?? collect()) as $i => $worker) {
        foreach(['CATEGORY' => 'lworker_category', 'SKILL' => 'lworker_skill', 'MALE' => 'lworker_male',
            'FEMALE' => 'lworker_female', 'WAGE' => 'lworker_wage', 'OVERTIME_HOURS' => 'lworker_overtime_hours',
            'OVERTIME_RATE' => 'lworker_overtime_rate'] as $key => $column) { $initial[$key][$i] = $worker->$column ?? 0; }
    }
    if(!$initial) { $initial = ['CATEGORY' => [0 => ''], 'SKILL' => [0 => 1], 'MALE' => [0 => 0], 'FEMALE' => [0 => 0], 'WAGE' => [0 => 0], 'OVERTIME_HOURS' => [0 => 0], 'OVERTIME_RATE' => [0 => 0]]; }
    $entries = old('workers', $initial);
@endphp
<div class="content"><div class="st-page-intro"><span class="st-eyebrow">LABOUR ATTENDANCE</span><h1>{{ $record ? 'Correct crew attendance' : 'Record crew attendance' }}</h1><p>Daily wages are per attending worker. Enter overtime hours per worker and the hourly rate separately.</p></div>
<form id="site-attendance-form" method="POST" action="{{ $record ? route('user.edit_labour_tracker', $record->ltracker_id) : route('user.add_labour_tracker') }}" data-attendance-form>
@csrf<input type="hidden" name="ltracker_project" value="{{ $workspaceProject->project_id }}">
<div class="card mb-4"><div class="card-body"><div class="row g-3">
    <div class="col-md-4"><label class="form-label" for="attendance-date">Attendance date</label><input id="attendance-date" class="form-control" type="date" name="ltracker_date" max="{{ date('Y-m-d') }}" value="{{ old('ltracker_date', $record?->ltracker_date ?? date('Y-m-d')) }}" required></div>
    <div class="col-md-4"><label class="form-label" for="attendance-subcontractor">Subcontractor</label><select id="attendance-subcontractor" name="ltracker_subcontractor" class="form-select" required><option value="">Select subcontractor</option>@foreach($subcontractors as $s)<option value="{{ $s->subcontractor_id }}" @selected(old('ltracker_subcontractor', $record?->ltracker_subcontractor) == $s->subcontractor_id)>{{ $s->subcontractor_name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label" for="attendance-incharge">Site in-charge</label><select id="attendance-incharge" name="ltracker_incharge" class="form-select" required><option value="">Select in-charge</option>@foreach($incharges as $u)<option value="{{ $u->user_id }}" @selected(old('ltracker_incharge', $record?->ltracker_incharge) == $u->user_id)>{{ $u->user_name }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label" for="attendance-shift">Shift</label><select id="attendance-shift" name="ltracker_shift" class="form-select"><option value="1">Day</option><option value="2" @selected(old('ltracker_shift', $record?->ltracker_shift) == 2)>Night</option></select></div>
    <div class="col-md-3"><label class="form-label" for="attendance-in">In time</label><input id="attendance-in" type="time" name="ltracker_in" class="form-control" value="{{ old('ltracker_in', $record ? substr($record->ltracker_in, 0, 5) : '08:00') }}" required></div>
    <div class="col-md-3"><label class="form-label" for="attendance-out">Out time</label><input id="attendance-out" type="time" name="ltracker_out" class="form-control" value="{{ old('ltracker_out', $record ? substr($record->ltracker_out, 0, 5) : '17:00') }}" required></div>
    <div class="col-md-3"><label class="form-label" for="attendance-hours">Shift hours</label><input id="attendance-hours" name="ltracker_thours" class="form-control" readonly></div>
    <div class="col-12"><label class="form-label" for="attendance-notes">Notes / correction reason</label><textarea id="attendance-notes" name="ltracker_remarks" class="form-control" maxlength="5000">{{ old('ltracker_remarks', $record?->ltracker_remarks) }}</textarea></div>
</div></div></div>
<div class="card mb-4"><div class="card-header"><h5 class="mb-0">Crew by work category</h5></div><div class="card-body"><div class="table-responsive"><table class="table st-attendance-table"><thead><tr><th>Work category</th><th>Skill</th><th>Male</th><th>Female</th><th>Daily wage / person (INR)</th>@if($overtimeEnabled)<th>OT hours / person</th><th>OT rate / hour (INR)</th>@endif<th>Total (INR)</th><th>Action</th></tr></thead><tbody data-attendance-rows>
@foreach($entries['CATEGORY'] as $index => $category)
<tr data-attendance-row>
    <td><select name="workers[CATEGORY][{{ $index }}]" class="form-select" aria-label="Work category" required><option value="">Select category</option>@foreach($categories as $c)<option value="{{ $c->category_id }}" @selected($category == $c->category_id)>{{ $c->category_name }}</option>@endforeach</select></td>
    <td><select name="workers[SKILL][{{ $index }}]" class="form-select" aria-label="Skill level">@foreach([1 => 'Skilled', 2 => 'Unskilled', 3 => 'Semi skilled'] as $key => $label)<option value="{{ $key }}" @selected(($entries['SKILL'][$index] ?? 1) == $key)>{{ $label }}</option>@endforeach</select></td>
    @foreach(['MALE' => 'Male workers', 'FEMALE' => 'Female workers', 'WAGE' => 'Daily wage'] as $field => $label)<td><input class="form-control" type="number" min="0" step="{{ $field === 'WAGE' ? '0.01' : '1' }}" name="workers[{{ $field }}][{{ $index }}]" value="{{ $entries[$field][$index] ?? 0 }}" aria-label="{{ $label }}" data-wage="{{ $field }}" required></td>@endforeach
    @if($overtimeEnabled)@foreach(['OVERTIME_HOURS' => 'Overtime hours per worker', 'OVERTIME_RATE' => 'Overtime hourly rate'] as $field => $label)<td><input class="form-control" type="number" min="0" @if($field === 'OVERTIME_HOURS') max="24" @endif step="0.01" name="workers[{{ $field }}][{{ $index }}]" value="{{ $entries[$field][$index] ?? 0 }}" aria-label="{{ $label }}" data-wage="{{ $field }}"></td>@endforeach @endif
    <td><input name="workers[TOTAL][{{ $index }}]" class="form-control" data-row-total readonly aria-label="Calculated crew cost"></td><td><button class="btn btn-sm btn-white" type="button" data-remove-attendance aria-label="Remove attendance row"><i class="ti ti-minus" aria-hidden="true"></i></button></td>
</tr>
@endforeach
</tbody></table></div><div class="d-flex gap-3 justify-content-between align-items-center flex-wrap"><button class="btn btn-white" type="button" data-add-attendance>Add crew row</button><label class="d-flex align-items-center gap-2">Total wages (INR)<input class="form-control st-wage-total" name="grand_total" data-attendance-total readonly></label></div></div></div>
<div class="d-flex flex-wrap gap-2"><button type="submit" name="submit" value="submit" class="btn btn-primary">{{ $record ? 'Save correction' : 'Record attendance' }}</button><a class="btn btn-white" href="{{ route('user.labour_trackers') }}">Back to attendance</a></div>
</form></div>
