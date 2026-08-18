@csrf
<input type="hidden" name="item_id" value="{{ $item->iitem_id }}">
<div class="modal-body pb-0">
   <div class="row">
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" id="choices-text-remove-button" required>
               <option value="1" {{ $item->iitem_status == 1 ? 'selected' : '' }}>Pending</option>
               <option value="2" {{ $item->iitem_status == 2 ? 'selected' : '' }}>Approved</option>
               <option value="3" {{ $item->iitem_status == 3 ? 'selected' : '' }}>Rejected</option>
               <option value="4" {{ $item->iitem_status == 4 ? 'selected' : '' }}>On Hold</option>
            </select>
            @if($errors->has('status'))
            <div>{{ $errors->first('status') }}</div>
            @endif
         </div>
      </div>
   </div>
</div>
<div class="modal-footer d-flex align-items-center gap-1">
   <button type="submit" name="submit" class="btn btn-primary" value="submit">Submit</button>
</div>
