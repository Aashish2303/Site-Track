@csrf
<input type="hidden" name="project_id" value="{{ $project->project_id }}">
<div class="modal-body pb-0">
   <div class="row">
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" id="choices-text-remove-button" required>
               <option value="1" {{ $project->project_status == 1 ? 'selected' : '' }}>Active</option>
               <option value="2" {{ $project->project_status == 2 ? 'selected' : '' }}>On Hold</option>
               <option value="3" {{ $project->project_status == 3 ? 'selected' : '' }}>Completed</option>
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
