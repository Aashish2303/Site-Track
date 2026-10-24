<!doctype html><html lang="en"><head><meta charset="utf-8"><title>SiteTrack report</title><style>
@page { margin: 28px; } body { font-family: 'DejaVu Sans', sans-serif; color: #20252b; font-size: 9px; } h1 { font-size: 20px; margin-bottom: 6px; } p { line-height: 1.6; color: #475569; } table { width: 100%; border-collapse: collapse; margin: 20px 0; table-layout: fixed; } th, td { padding: 7px 5px; border: 1px solid #d5dae0; text-align: left; overflow-wrap: break-word; } th { background: #fff1cd; font-size: 8px; } thead { display: table-header-group; } tr { page-break-inside: avoid; }
</style></head><body><h1>{{ $category === 'materials' ? 'Material Usage Report' : 'Labour and Payment Report' }}</h1><p><strong>SiteTrack Pro · {{ $projectLabel }}</strong><br>{{ $start->format('d M Y') }} to {{ $end->format('d M Y') }}</p>
@include('user.reports.table')
@include('user.reports.notes')
</body></html>
