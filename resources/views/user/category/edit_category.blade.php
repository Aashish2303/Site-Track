@csrf
<input type="hidden" name="category_id" value="{{ $category->category_id }}">
<div class="modal-body pb-0">
   <div class="row">
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category_name" class="form-control" id="choices-text-remove-button" value="{{ $category->category_name }}" autocomplete="off">
            @if($errors->has('category_name'))
            <div>{{ $errors->first('category_name') }}</div>
            @endif
         </div>
      </div>
   </div>
</div>
<div class="modal-footer d-flex align-items-center gap-1">
   <button type="submit" name="submit" class="btn btn-primary" value="submit">Submit</button>
</div>
