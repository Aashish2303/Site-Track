@csrf
<input type="hidden" name="dprogress_id" value="{{ $daily_progress->dprogress_id }}">
<div class="modal-body pb-0">
   <div class="row">
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" id="choices-text-remove-button" required>
               <option value="1" {{ $daily_progress->dprogress_status == 1 ? 'selected' : '' }}>Completed</option>
               <option value="2" {{ $daily_progress->dprogress_status == 2 ? 'selected' : '' }}>Partial</option>
               <option value="3" {{ $daily_progress->dprogress_status == 3 ? 'selected' : '' }}>Delayed - Carry Forward</option>
               <option value="4" {{ $daily_progress->dprogress_status == 4 ? 'selected' : '' }}>Cancelled</option>
               <option value="5" {{ $daily_progress->dprogress_status == 5 ? 'selected' : '' }}>Not Started</option>
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
