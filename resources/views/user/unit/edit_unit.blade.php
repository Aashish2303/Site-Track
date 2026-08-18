@csrf
<input type="hidden" name="unit_id" value="{{ $unit->unit_id }}">
<div class="modal-body pb-0">
   <div class="row">
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Unit</label>
            <input type="text" name="unit_name" class="form-control" id="choices-text-remove-button" value="{{ $unit->unit_name }}" autocomplete="off">
            @if($errors->has('unit_name'))
            <div>{{ $errors->first('unit_name') }}</div>
            @endif
         </div>
      </div>
   </div>
</div>
<div class="modal-footer d-flex align-items-center gap-1">
   <button type="submit" name="submit" class="btn btn-primary" value="submit">Submit</button>
</div>
