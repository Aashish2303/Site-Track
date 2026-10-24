@if(app(\App\Services\WorkflowSchema::class)->ready())
<div class="card mb-4"><div class="card-body">
    <h5>Progress evidence</h5><p class="text-muted">Reported quantities remain pending until another authorised reviewer verifies them. Changing an approved entry creates a new revision and removes its current approval.</p>
    <label for="progress-photos" class="form-label">Dated site photos</label><input id="progress-photos" type="file" name="photos[]" class="form-control" accept="image/jpeg,image/png,image/webp" multiple><p class="text-muted mt-2">Up to 5 photos per submission, 4 MB each. The report date and upload time are retained with the evidence.</p>
    @if(isset($daily_progress))
        <input type="hidden" name="revision" value="{{ old('revision', \Illuminate\Support\Facades\DB::table('progress_reviews')->where('progress_id', $daily_progress->dprogress_id)->value('revision') ?? 1) }}">
        <label for="correction-note" class="form-label">Reason for this correction</label><textarea id="correction-note" name="correction_note" class="form-control" maxlength="2000" required>{{ old('correction_note') }}</textarea>
    @endif
</div></div>
@endif
