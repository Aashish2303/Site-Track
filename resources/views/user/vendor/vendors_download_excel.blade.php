<table class="table table-bordered">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Category</th>
            <th>Vendor</th>
            <th>Company</th>
            <th>Mobile No</th>
            <th>Alternative No</th>
            <th>Location</th>
            <th>Material/Service Type</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vendors as $vendor)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $vendor->category->mcategory_name }}</td>
            <td>{{ $vendor->vendor_name }}</td>
            <td>{{ $vendor->vendor_company }}</td>
            <td>{{ $vendor->vendor_mobile }}</td>
            <td>{{ $vendor->vendor_altno }}</td>
            <td>{{ $vendor->vendor_location }}</td>
            <td>{{ $vendor->vendor_type }}</td>
            <td>{{ $vendor->vendor_remarks }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
