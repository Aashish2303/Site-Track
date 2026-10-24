<table class="table st-report-table">
@if($category === 'materials')
    <thead><tr><th>Project</th><th>Material</th><th>Unit</th><th>Opening stock</th><th>Received</th><th>Used</th><th>Returned</th><th>Closing stock</th><th>Usage cost (INR)</th></tr></thead>
    <tbody>@forelse($rows as $row)<tr><td>{{ $row['project'] }}</td><td>{{ $row['material'] }}</td><td>{{ $row['unit'] }}</td><td>{{ $row['opening'] }}</td><td>{{ $row['received'] }}</td><td>{{ $row['used'] }}</td><td>{{ $row['returned'] }}</td><td>{{ $row['closing'] }}</td><td>{{ $row['cost'] === null ? 'Not fully priced' : number_format($row['cost'], 2, '.', '') }}</td></tr>@empty<tr><td colspan="9">No material movements match these filters.</td></tr>@endforelse</tbody>
@else
    <thead><tr><th>Project</th><th>Subcontractor</th><th>Work category</th><th>Worker-shifts</th><th>Opening balance (INR)</th><th>Base wages (INR)</th><th>Overtime (INR)</th><th>Advances (INR)</th><th>Payments (INR)</th><th>Closing balance (INR)</th></tr></thead>
    <tbody>@forelse($rows as $row)<tr><td>{{ $row['project'] }}</td><td>{{ $row['subcontractor'] }}</td><td>{{ $row['category'] }}</td><td>{{ $row['attendance'] }}</td>@foreach(['opening', 'base', 'overtime', 'advance', 'paid', 'balance'] as $field)<td>{{ number_format($row[$field], 2, '.', '') }}</td>@endforeach</tr>@empty<tr><td colspan="10">No labour attendance or payments match these filters.</td></tr>@endforelse</tbody>
@endif
</table>
