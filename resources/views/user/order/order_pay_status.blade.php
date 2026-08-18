@csrf
<input type="hidden" name="order_id" value="{{ $order->order_id }}">
<div class="modal-body pb-0">
   <div class="row">
      <div class="col-lg-12">
         <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" id="choices-text-remove-button" required>
               <option value="1" {{ $order->order_pay_status == 1 ? 'selected' : '' }}>Pending</option>
               <option value="2" {{ $order->order_pay_status == 2 ? 'selected' : '' }}>Partial</option>
               <option value="5" {{ $order->order_pay_status == 3 ? 'selected' : '' }}>Paid</option>
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
