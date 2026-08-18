@csrf
<input type="hidden" name="icategory_id" value="{{ $category->icategory_id }}">
<div class="modal-body pb-0">
   <div class="row">
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="icategory_name" class="form-control" id="choices-text-remove-button" value="{{ $category->icategory_name }}" autocomplete="off">
            @if($errors->has('icategory_name'))
            <div>{{ $errors->first('icategory_name') }}</div>
            @endif
         </div>
      </div>
   </div>
</div>
<div class="modal-footer d-flex align-items-center gap-1">
   <button type="submit" name="submit" class="btn btn-primary" value="submit">Submit</button>
</div>
