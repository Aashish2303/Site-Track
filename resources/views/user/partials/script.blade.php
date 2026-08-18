<script src="{{ asset(config('constants.admin_path').'js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/moment.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/apexchart/chart-data.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/chartjs/chart.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/chartjs/chart-data.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/jquery.validate.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/form_validations.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/script.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/theme-script.js') }}"></script>
<script>
@if(Session::has('success'))
Swal.fire({
    title: "Success!",
    text: "{{ Session::get('success') }}",
    type: "success",
    confirmButtonClass: "btn btn-primary",
    buttonsStyling: !1,
    icon: "success"
});
@endif
@if(Session::has('error'))
Swal.fire({
    title: "Warning!",
    text: "{{ Session::get('error') }}",
    type: "warning",
    confirmButtonClass: "btn btn-primary",
    buttonsStyling: !1,
    icon: "warning"
});
@endif
</script>