<table><tr><th colspan="10">SiteTrack Pro — {{ $category === 'materials' ? 'Material Usage Report' : 'Labour and Payment Report' }}</th></tr><tr><td colspan="10">{{ $projectLabel }} | {{ $start->format('d M Y') }} to {{ $end->format('d M Y') }}</td></tr></table>
@include('user.reports.table')
<table><tr><td colspan="10">@include('user.reports.notes')</td></tr></table>
