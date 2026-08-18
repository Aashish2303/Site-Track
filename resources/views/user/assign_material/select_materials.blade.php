<option>Select</option>
@foreach($materials as $material)
<option value="{{ $material->material_id }}">{{ $material->material_name }}</option>
@endforeach