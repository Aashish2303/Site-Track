@if($category === 'materials')
<p>Opening stock is the balance before {{ $start->format('d M Y') }}. Received means transfers assigned to the site. Closing stock = opening + received − used − returned. Quantities remain separate by project, material and unit. Usage cost uses the rates entered with consumption; missing rates are marked as not fully priced.</p>
@else
<p>Worker-shifts count crew attendance entries, not unique individual workers. Wages follow the recorded daily rate; overtime is workers × hours per worker × hourly rate. Closing balance = opening balance + wages + overtime − advances − payments. A negative balance is a prepaid credit. Existing contractor ledgers remain separate to avoid counting a payment twice.</p>
@endif
