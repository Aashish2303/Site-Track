@csrf
<input type="hidden" name="issue_id" value="{{ $issue->issue_id }}">
<div class="modal-body pb-0">
   <div class="row">
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" id="choices-text-remove-button" required>
               <option value="1" {{ $issue->issue_status == 1 ? 'selected' : '' }}>Open</option>
               <option value="2" {{ $issue->issue_status == 2 ? 'selected' : '' }}>In Progress</option>
               <option value="3" {{ $issue->issue_status == 3 ? 'selected' : '' }}>Resolved</option>
               <option value="4" {{ $issue->issue_status == 4 ? 'selected' : '' }}>Closed</option>
            </select>
            @if($errors->has('status'))
            <div>{{ $errors->first('status') }}</div>
            @endif
         </div>
      </div>
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Remarks</label>
            <input type="text" name="issue_sremarks" class="form-control" value="{{ $issue->issue_sremarks }}" autocomplete="off">
            @if($errors->has('issue_sremarks'))
            <div>{{ $errors->first('issue_sremarks') }}</div>
            @endif
         </div>
      </div>
   </div>
</div>
<div class="modal-footer d-flex align-items-center gap-1">
   <button type="submit" name="submit" class="btn btn-primary" value="submit">Submit</button>
</div>
