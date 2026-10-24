<option value="">Select</option>
@foreach($boqs as $boq)
@php $completed_boq = 0; @endphp
@if(isset($dboqs[$boq->pboq_id]))
@php $completed_boq = $dboqs[$boq->pboq_id]; @endphp
@endif
<option value="{{ $boq->pboq_id }}">{{ $boq->pboq_item }} ({{ $completed_boq }}/{{ $boq->pboq_qty }} Approved)</option>
@endforeach