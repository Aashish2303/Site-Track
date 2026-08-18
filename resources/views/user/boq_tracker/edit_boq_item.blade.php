@csrf
<input type="hidden" name="pboq_id" value="{{ $boq->pboq_id }}">
<div class="modal-body pb-0">
   <div class="row">
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Item</label>
            <input type="text" name="pboq_item" class="form-control" id="choices-text-remove-button" value="{{ $boq->pboq_item }}" autocomplete="off">
            @if($errors->has('pboq_item'))
            <div>{{ $errors->first('pboq_item') }}</div>
            @endif
         </div>
      </div>
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Unit</label>
            <input type="text" name="pboq_unit" class="form-control" id="choices-text-remove-button" value="{{ $boq->pboq_unit }}" autocomplete="off">
            @if($errors->has('pboq_unit'))
            <div>{{ $errors->first('pboq_unit') }}</div>
            @endif
         </div>
      </div>
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">BOQ Quantity</label>
            <input type="number" name="pboq_qty" class="form-control" id="choices-text-remove-button" value="{{ $boq->pboq_qty }}" autocomplete="off">
            @if($errors->has('pboq_qty'))
            <div>{{ $errors->first('pboq_qty') }}</div>
            @endif
         </div>
      </div>
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Rate</label>
            <input type="number" name="pboq_rate" class="form-control" id="choices-text-remove-button" value="{{ $boq->pboq_rate }}" autocomplete="off">
            @if($errors->has('pboq_rate'))
            <div>{{ $errors->first('pboq_rate') }}</div>
            @endif
         </div>
      </div>
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Remarks</label>
            <input type="text" name="pboq_remarks" class="form-control" id="choices-text-remove-button" value="{{ $boq->pboq_remarks }}" autocomplete="off">
            @if($errors->has('pboq_remarks'))
            <div>{{ $errors->first('pboq_remarks') }}</div>
            @endif
         </div>
      </div>
   </div>
</div>
<div class="modal-footer d-flex align-items-center gap-1">
   <button type="submit" name="submit" class="btn btn-primary" value="submit">Submit</button>
</div>
