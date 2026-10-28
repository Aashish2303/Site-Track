<option value="">{{ $materials->isEmpty() ? 'No materials in this category' : 'Select material' }}</option>
@foreach($materials as $material)
<option value="{{ $material->material_id }}" @selected($materials->count() === 1)>{{ $material->material_name }}</option>
@endforeach
