@csrf
<input type="hidden" name="mcategory_id" value="{{ $category->mcategory_id }}">
<div class="modal-body pb-0">
   <div class="row">
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="mcategory_name" class="form-control" id="choices-text-remove-button" value="{{ $category->mcategory_name }}" autocomplete="off">
            @if($errors->has('mcategory_name'))
            <div>{{ $errors->first('mcategory_name') }}</div>
            @endif
         </div>
      </div>
   </div>
</div>
<div class="modal-footer d-flex align-items-center gap-1">
   <button type="submit" name="submit" class="btn btn-primary" value="submit">Submit</button>
</div>
