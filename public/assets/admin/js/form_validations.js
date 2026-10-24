$(document).ready(function () {

    $.validator.addMethod("usernamePattern", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9_.]+$/.test(value);
    }, "Only letters, numbers, underscores, and dots are allowed.");

    $('#login_form').validate({
        rules: {
            user_lname: {
                required: true
            },
            user_password: {
                required: true
            }
        },
        messages :{
            user_lname : {
                required : '<small style="color:red">Please Enter Username</small>'
            },
            user_password : {
                required : '<small style="color:red">Please Enter Password</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "user_lname")
            {
                error.appendTo($("#user_lname_error"));
            }
            else if(element.attr("name") == "user_password")
            {
                error.appendTo($("#user_password_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#forgot_password_form').validate({
        rules: {
            user_email: {
                required: true,
                email: true
            }
        },
        messages :{
            user_email : {
                required : '<small style="color:red">Please Enter Email Address</small>',
                email : '<small style="color:red">Please Enter Valid Email Address</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "user_email")
            {
                error.appendTo($("#user_email_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#profile_form').validate({
        rules: {
            user_name: {
                required: true
            },
            user_lname: {
                required: true,
                usernamePattern: true
            },
            user_email: {
                required: true,
                email: true
            },
            user_phone: {
                required: true
            }
        },
        messages :{
            user_name : {
                required : '<small style="color:red">Please Enter Name</small>'
            },
            user_lname : {
                required : '<small style="color:red">Please Enter Username</small>',
                usernamePattern : '<small style="color:red">Please Enter Only Letters, Numbers, Underscores & Dots</small>'
            },
            user_email : {
                required : '<small style="color:red">Please Enter Email Address</small>',
                email : '<small style="color:red">Please Enter Valid Email Address</small>'
            },
            user_phone : {
                required : '<small style="color:red">Please Enter Mobile No</small>'
            }
        }
    });

    $('#change_password_form').validate({
        rules: {
            current_password: {
                required: true,

            },
            new_password: {
                required: true,
                minlength: 12
            },
            confirm_password: {
                required: true,
                equalTo: "#new_password"
            }
        },
        messages :{
            current_password : {
                required : '<small style="color:red">Please Enter Current Password</small>',
            },
            new_password : {
                required : '<small style="color:red">Please Enter New Password</small>',
                minlength : '<small style="color:red">Please Enter At Least 12 Characters</small>'
            },
            confirm_password : {
                required : '<small style="color:red">Please Enter Confirm Password</small>',
                equalTo : '<small style="color:red">New password and confirm password does not match.</small>'
            }
        }
    });

    $('#project_form').validate({
        rules: {
            project_name: {
                required: true
            },
            project_location: {
                required: true
            },
            project_client: {
                required: true
            },
            project_engineer: {
                required: true
            },
            project_start: {
                required: true
            },
            project_end: {
                required: true
            },
            project_budget: {
                required: true
            },
            boq_file: {
                required: true
            }
        },
        messages :{
            project_name : {
                required : '<small style="color:red">Please Enter Project</small>'
            },
            project_location : {
                required : '<small style="color:red">Please Enter Location</small>'
            },
            project_client : {
                required : '<small style="color:red">Please Select Client</small>'
            },
            project_engineer : {
                required : '<small style="color:red">Please Select Site Engineer</small>'
            },
            project_start : {
                required : '<small style="color:red">Please Select Start Date</small>'
            },
            project_end : {
                required : '<small style="color:red">Please Select End Date</small>'
            },
            project_budget : {
                required : '<small style="color:red">Please Enter Budget</small>'
            },
            boq_file : {
                required : '<small style="color:red">Please Select BOQ Excel File</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "project_client")
            {
                error.appendTo($("#project_client_error"));
            }
            else if(element.attr("name") == "project_engineer")
            {
                error.appendTo($("#project_engineer_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            
            Swal.fire({
                title: 'Loading...',
                text: 'Please Wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            form.submit();
        }
    });

    $('#edit_project_form').validate({
        rules: {
            project_name: {
                required: true
            },
            project_location: {
                required: true
            },
            project_client: {
                required: true
            },
            project_engineer: {
                required: true
            },
            project_start: {
                required: true
            },
            project_end: {
                required: true
            },
            project_budget: {
                required: true
            }
        },
        messages :{
            project_name : {
                required : '<small style="color:red">Please Enter Project</small>'
            },
            project_location : {
                required : '<small style="color:red">Please Enter Location</small>'
            },
            project_client : {
                required : '<small style="color:red">Please Select Client</small>'
            },
            project_engineer : {
                required : '<small style="color:red">Please Select Site Engineer</small>'
            },
            project_start : {
                required : '<small style="color:red">Please Select Start Date</small>'
            },
            project_end : {
                required : '<small style="color:red">Please Select End Date</small>'
            },
            project_budget : {
                required : '<small style="color:red">Please Enter Budget</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "project_client")
            {
                error.appendTo($("#project_client_error"));
            }
            else if(element.attr("name") == "project_engineer")
            {
                error.appendTo($("#project_engineer_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#daily_work_form').validate({
        rules: {
            dwork_date: {
                required: true
            },
            dwork_project: {
                required: true
            },
            dwork_description: {
                required: true
            },
            dwork_location: {
                required: true
            },
            dwork_qty: {
                required: true
            },
            dwork_category: {
                required: true
            },
            dwork_subcontractor: {
                required: true
            },
            dwork_incharge: {
                required: true
            }
        },
        messages :{
            dwork_date : {
                required : '<small style="color:red">Please Select Date</small>'
            },
            dwork_project : {
                required : '<small style="color:red">Please Select Project</small>'
            },
            dwork_description : {
                required : '<small style="color:red">Please Enter Description</small>'
            },
            dwork_location : {
                required : '<small style="color:red">Please Enter Location</small>'
            },
            dwork_qty : {
                required : '<small style="color:red">Please Enter Quantity</small>'
            },
            dwork_category : {
                required : '<small style="color:red">Please Select Category</small>'
            },
            dwork_subcontractor : {
                required : '<small style="color:red">Please Select Subcontractor</small>'
            },
            dwork_incharge : {
                required : '<small style="color:red">Please Select Site Incharge</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "dwork_project")
            {
                error.appendTo($("#dwork_project_error"));
            }
            else if(element.attr("name") == "dwork_category")
            {
                error.appendTo($("#dwork_category_error"));
            }
            else if(element.attr("name") == "dwork_subcontractor")
            {
                error.appendTo($("#dwork_subcontractor_error"));
            }
            else if(element.attr("name") == "dwork_incharge")
            {
                error.appendTo($("#dwork_incharge_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#daily_progress_form').validate({
        rules: {
            dprogress_date: {
                required: true
            },
            dprogress_project: {
                required: true
            },
            dprogress_category: {
                required: true
            },
            dprogress_description: {
                required: true
            },
            dprogress_boq: {
                required: true
            },
            dprogress_qty: {
                required: true
            },
            dprogress_location: {
                required: true
            },
            dprogress_subcontractor: {
                required: true
            },
            dprogress_incharge: {
                required: true
            }
        },
        messages :{
            dprogress_date : {
                required : '<small style="color:red">Please Select Date</small>'
            },
            dprogress_project : {
                required : '<small style="color:red">Please Select Project</small>'
            },
            dprogress_category : {
                required : '<small style="color:red">Please Select Category</small>'
            },
            dprogress_description : {
                required : '<small style="color:red">Please Enter Description</small>'
            },
            dprogress_boq : {
                required : '<small style="color:red">Please Select BOQ Item</small>'
            },
            dprogress_qty : {
                required : '<small style="color:red">Please Enter Quantity Done</small>'
            },
            dprogress_location : {
                required : '<small style="color:red">Please Enter Location</small>'
            },
            dprogress_subcontractor : {
                required : '<small style="color:red">Please Select Subcontractor</small>'
            },
            dprogress_incharge : {
                required : '<small style="color:red">Please Select Site Incharge</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "dprogress_project")
            {
                error.appendTo($("#dprogress_project_error"));
            }
            else if(element.attr("name") == "dprogress_category")
            {
                error.appendTo($("#dprogress_category_error"));
            }
            else if(element.attr("name") == "dprogress_boq")
            {
                error.appendTo($("#dprogress_boq_error"));
            }
            else if(element.attr("name") == "dprogress_subcontractor")
            {
                error.appendTo($("#dprogress_subcontractor_error"));
            }
            else if(element.attr("name") == "dprogress_incharge")
            {
                error.appendTo($("#dprogress_incharge_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });
    
    $('#add_boq_item_form').validate({
        rules: {
            pboq_item: {
                required: true
            },
            pboq_unit: {
                required: true
            },
            pboq_qty: {
                required: true
            },
            pboq_rate: {
                required: true
            }
        },
        messages :{
            pboq_item : {
                required : '<small style="color:red">Please Enter Item</small>'
            },
            pboq_unit : {
                required : '<small style="color:red">Please Enter Unit</small>'
            },
            pboq_qty : {
                required : '<small style="color:red">Please Enter Quantity</small>'
            },
            pboq_rate : {
                required : '<small style="color:red">Please Enter Rate</small>'
            }
        }
    });

    $('#edit_boq_item_form').validate({
        rules: {
            pboq_item: {
                required: true
            },
            pboq_unit: {
                required: true
            },
            pboq_qty: {
                required: true
            },
            pboq_rate: {
                required: true
            }
        },
        messages :{
            pboq_item : {
                required : '<small style="color:red">Please Enter Item</small>'
            },
            pboq_unit : {
                required : '<small style="color:red">Please Enter Unit</small>'
            },
            pboq_qty : {
                required : '<small style="color:red">Please Enter Quantity</small>'
            },
            pboq_rate : {
                required : '<small style="color:red">Please Enter Rate</small>'
            }
        }
    });

    $('#assign_material_form').validate({
        rules: {
            amaterial_date: {
                required: true
            },
            amaterial_project: {
                required: true
            },
            amaterial_category: {
                required: true
            },
            amaterial_material: {
                required: true
            },
            amaterial_unit_name: {
                required: true
            },
            amaterial_qty: {
                required: true
            },
            available_qty: {
                required: true
            }
        },
        messages :{
            amaterial_date : {
                required : '<small style="color:red">Please Select Date</small>'
            },
            amaterial_project : {
                required : '<small style="color:red">Please Select Project</small>'
            },
            amaterial_category : {
                required : '<small style="color:red">Please Select Category</small>'
            },
            amaterial_material : {
                required : '<small style="color:red">Please Select Material</small>'
            },
            amaterial_unit_name : {
                required : '<small style="color:red">Please Select Material</small>'
            },
            amaterial_qty : {
                required : '<small style="color:red">Please Enter Quantity</small>'
            },
            available_qty : {
                required : '<small style="color:red">Please Enter Available Quantity</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "amaterial_project")
            {
                error.appendTo($("#amaterial_project_error"));
            }
            else if(element.attr("name") == "amaterial_category")
            {
                error.appendTo($("#amaterial_category_error"));
            }
            else if(element.attr("name") == "amaterial_material")
            {
                error.appendTo($("#amaterial_material_error"));
            }
            else if(element.attr("name") == "amaterial_unit")
            {
                error.appendTo($("#amaterial_unit_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            
            var available_qty = parseFloat($("#available_qty").val()) || 0;
            var amaterial_qty = parseFloat($("#amaterial_qty").val()) || 0;
            
            if(available_qty < amaterial_qty)
            {
                Swal.fire({
                    title: 'Error',
                    text: 'Assigned quantity cannot exceed available quantity',
                    icon: 'error'
                });
                
                return false;
            }

            form.submit();
        }
    });
    
    $('#return_material_form').validate({
        rules: {
            rmaterial_date: {
                required: true
            },
            rmaterial_project: {
                required: true
            },
            rmaterial_category: {
                required: true
            },
            rmaterial_material: {
                required: true
            },
            rmaterial_unit_name: {
                required: true
            },
            rmaterial_qty: {
                required: true
            }
        },
        messages :{
            rmaterial_date : {
                required : '<small style="color:red">Please Select Date</small>'
            },
            rmaterial_project : {
                required : '<small style="color:red">Please Select Project</small>'
            },
            rmaterial_category : {
                required : '<small style="color:red">Please Select Category</small>'
            },
            rmaterial_material : {
                required : '<small style="color:red">Please Select Material</small>'
            },
            rmaterial_unit_name : {
                required : '<small style="color:red">Please Select Material</small>'
            },
            rmaterial_qty : {
                required : '<small style="color:red">Please Enter Quantity</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "rmaterial_project")
            {
                error.appendTo($("#rmaterial_project_error"));
            }
            else if(element.attr("name") == "rmaterial_category")
            {
                error.appendTo($("#rmaterial_category_error"));
            }
            else if(element.attr("name") == "rmaterial_material")
            {
                error.appendTo($("#rmaterial_material_error"));
            }
            else if(element.attr("name") == "rmaterial_unit")
            {
                error.appendTo($("#rmaterial_unit_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });
    
    $('#order_form').validate({
        rules: {
            order_date: {
                required: true
            },
            order_po_no: {
                required: true
            },
            order_vendor: {
                required: true
            },
            order_material: {
                required: true
            },
            order_unit: {
                required: true
            },
            order_qty: {
                required: true
            },
            order_rate: {
                required: true
            }
        },
        messages :{
            order_date : {
                required : '<small style="color:red">Please Select Date</small>'
            },
            order_po_no : {
                required : '<small style="color:red">Please Enter PO Number</small>'
            },
            order_vendor : {
                required : '<small style="color:red">Please Select Vendor</small>'
            },
            order_material : {
                required : '<small style="color:red">Please Select Material</small>'
            },
            order_unit : {
                required : '<small style="color:red">Please Select Unit</small>'
            },
            order_qty : {
                required : '<small style="color:red">Please Enter Quantity</small>'
            },
            order_rate : {
                required : '<small style="color:red">Please Enter Rate</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "order_vendor")
            {
                error.appendTo($("#order_vendor_error"));
            }
            else if(element.attr("name") == "order_material")
            {
                error.appendTo($("#order_material_error"));
            }
            else if(element.attr("name") == "order_unit")
            {
                error.appendTo($("#order_unit_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });    

    $('#order_delivery_form').validate({
        rules: {
            odelivery_date: {
                required: true
            },
            odelivery_qty: {
                required: true
            },
            odelivery_dc: {
                required: true
            }
        },
        messages :{
            odelivery_date : {
                required : '<small style="color:red">Please Select Date</small>'
            },
            odelivery_qty : {
                required : '<small style="color:red">Please Enter Quantity</small>'
            },
            odelivery_dc : {
                required : '<small style="color:red">Please Enter DC</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "material_category")
            {
                error.appendTo($("#material_category_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#material_form').validate({
        rules: {
            material_category: {
                required: true
            },
            material_name: {
                required: true
            },
            material_unit: {
                required: true
            }
        },
        messages :{
            material_category : {
                required : '<small style="color:red">Please Select Category</small>'
            },
            material_name : {
                required : '<small style="color:red">Please Enter Material</small>'
            },
            material_unit : {
                required : '<small style="color:red">Please Select Unit</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "material_category")
            {
                error.appendTo($("#material_category_error"));
            }
            else if(element.attr("name") == "material_unit")
            {
                error.appendTo($("#material_unit_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#indent_form').validate({
        rules: {
            indent_date: {
                required: true
            },
            indent_project: {
                required: true
            },
            indent_urgency: {
                required: true
            },
            indent_engineer: {
                required: true
            },
            indent_notes: {
                required: true
            },
            indent_napproval: {
                required: true
            }
        },
        messages :{
            indent_date : {
                required : '<small style="color:red">Please Select Date</small>'
            },
            indent_project : {
                required : '<small style="color:red">Please Select Project</small>'
            },
            indent_urgency : {
                required : '<small style="color:red">Please Select Urgency</small>'
            },
            indent_engineer : {
                required : '<small style="color:red">Please Enter Site Engineer</small>'
            },
            indent_notes : {
                required : '<small style="color:red">Please Enter Notes</small>'
            },
            indent_napproval : {
                required : '<small style="color:red">Please Enter Note for Approval</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "indent_project")
            {
                error.appendTo($("#indent_project_error"));
            }
            else if(element.attr("name") == "indent_urgency")
            {
                error.appendTo($("#indent_urgency_error"));
            }
            else if(element.attr("name") == "indent_engineer")
            {
                error.appendTo($("#indent_engineer_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#indent_quotation_form').validate({
        rules: {
            iquote_vendor: {
                required: true
            },
            iquote_rate: {
                required: true
            },
            iquote_total: {
                required: true
            },
            iquote_delivery: {
                required: true
            }
        },
        messages :{
            iquote_vendor : {
                required : '<small style="color:red">Please Select Vendor</small>'
            },
            iquote_rate : {
                required : '<small style="color:red">Please Enter Rate</small>'
            },
            iquote_total : {
                required : '<small style="color:red">Please Enter Total</small>'
            },
            iquote_delivery : {
                required : '<small style="color:red">Please Enter Delivery</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "iquote_vendor")
            {
                error.appendTo($("#iquote_vendor_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#issue_form').validate({
        rules: {
            issue_date: {
                required: true
            },
            issue_project: {
                required: true
            },
            issue_title: {
                required: true
            },
            issue_description: {
                required: true
            },
            issue_category: {
                required: true
            },
            issue_priority: {
                required: true
            },
            issue_assign: {
                required: true
            }
        },
        messages :{
            issue_date : {
                required : '<small style="color:red">Please Select Date</small>'
            },
            issue_project : {
                required : '<small style="color:red">Please Select Project</small>'
            },
            issue_title : {
                required : '<small style="color:red">Please Enter Title</small>'
            },
            issue_description : {
                required : '<small style="color:red">Please Enter Description</small>'
            },
            issue_category : {
                required : '<small style="color:red">Please Select Category</small>'
            },
            issue_priority : {
                required : '<small style="color:red">Please Select Priority</small>'
            },
            issue_assign : {
                required : '<small style="color:red">Please Select Assigned To</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "issue_project")
            {
                error.appendTo($("#issue_project_error"));
            }
            else if(element.attr("name") == "issue_category")
            {
                error.appendTo($("#issue_category_error"));
            }
            else if(element.attr("name") == "issue_priority")
            {
                error.appendTo($("#issue_priority_error"));
            }
            else if(element.attr("name") == "issue_assign")
            {
                error.appendTo($("#issue_assign_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#subcontractor_ledger_form').validate({
        rules: {
            sledger_subcontractor: {
                required: true
            },
            sledger_project: {
                required: true
            },
            sledger_date: {
                required: true
            },
            sledger_type: {
                required: true
            },
            sledger_wpno: {
                required: true
            },
            sledger_description: {
                required: true
            },
            sledger_total: {
                required: true
            },
            sledger_invoice: {
                required: true
            },
            sledger_paid: {
                required: true
            }
        },
        messages :{
            sledger_subcontractor : {
                required : '<small style="color:red">Please Select Subcontractor</small>'
            },
            sledger_project : {
                required : '<small style="color:red">Please Select Project</small>'
            },
            sledger_date : {
                required : '<small style="color:red">Please Select Date</small>'
            },
            sledger_type : {
                required : '<small style="color:red">Please Select Type</small>'
            },
            sledger_wpno : {
                required : '<small style="color:red">Please Enter WO / PO Number</small>'
            },
            sledger_description : {
                required : '<small style="color:red">Please Enter Description</small>'
            },
            sledger_total : {
                required : '<small style="color:red">Please Enter Total Amount</small>'
            },
            sledger_invoice : {
                required : '<small style="color:red">Please Enter Invoice Amount</small>'
            },
            sledger_paid : {
                required : '<small style="color:red">Please Enter Paid Amount</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "sledger_subcontractor")
            {
                error.appendTo($("#sledger_subcontractor_error"));
            }
            else if(element.attr("name") == "sledger_project")
            {
                error.appendTo($("#sledger_project_error"));
            }
            else if(element.attr("name") == "sledger_type")
            {
                error.appendTo($("#sledger_type_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#vendor_ledger_form').validate({
        rules: {
            vledger_vendor: {
                required: true
            },
            vledger_project: {
                required: true
            },
            vledger_date: {
                required: true
            },
            vledger_type: {
                required: true
            },
            vledger_pono: {
                required: true
            },
            vledger_description: {
                required: true
            },
            vledger_po_total: {
                required: true
            },
            vledger_invoice: {
                required: true
            },
            vledger_paid: {
                required: true
            }
        },
        messages :{
            vledger_vendor : {
                required : '<small style="color:red">Please Select Vendor</small>'
            },
            vledger_project : {
                required : '<small style="color:red">Please Select Project</small>'
            },
            vledger_date : {
                required : '<small style="color:red">Please Select Date</small>'
            },
            vledger_type : {
                required : '<small style="color:red">Please Select Type</small>'
            },
            vledger_pono : {
                required : '<small style="color:red">Please Enter PO Number</small>'
            },
            vledger_description : {
                required : '<small style="color:red">Please Enter Description</small>'
            },
            vledger_po_total : {
                required : '<small style="color:red">Please Enter PO Amount</small>'
            },
            vledger_invoice : {
                required : '<small style="color:red">Please Enter Invoice Amount</small>'
            },
            vledger_paid : {
                required : '<small style="color:red">Please Enter Paid Amount</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "vledger_vendor")
            {
                error.appendTo($("#vledger_vendor_error"));
            }
            else if(element.attr("name") == "vledger_project")
            {
                error.appendTo($("#vledger_project_error"));
            }
            else if(element.attr("name") == "vledger_type")
            {
                error.appendTo($("#vledger_type_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#client_form').validate({
        rules: {
            client_name: {
                required: true
            },
            client_email: {
                required: true,
                email: true
            },
            client_phone: {
                required: true
            },
            client_address: {
                required: true
            }
        },
        messages :{
            client_name : {
                required : '<small style="color:red">Please Enter Name</small>'
            },
            client_email : {
                required : '<small style="color:red">Please Enter Email Address</small>',
                email : '<small style="color:red">Please Enter Valid Email Address</small>'
            },
            client_phone : {
                required : '<small style="color:red">Please Enter Phone</small>'
            },
            client_address : {
                required : '<small style="color:red">Please Enter Address</small>'
            }
        }
    });

    $('#subcontractor_form').validate({
        rules: {
            subcontractor_name: {
                required: true
            },
            subcontractor_email: {
                required: true,
                email: true
            },
            subcontractor_phone: {
                required: true
            },
            subcontractor_address: {
                required: true
            }
        },
        messages :{
            subcontractor_name : {
                required : '<small style="color:red">Please Enter Name</small>'
            },
            subcontractor_email : {
                required : '<small style="color:red">Please Enter Email Address</small>',
                email : '<small style="color:red">Please Enter Valid Email Address</small>'
            },
            subcontractor_phone : {
                required : '<small style="color:red">Please Enter Phone</small>'
            },
            subcontractor_address : {
                required : '<small style="color:red">Please Enter Address</small>'
            }
        }
    });

    $('#vendor_form').validate({
        rules: {
            vendor_category: {
                required: true
            },
            vendor_name: {
                required: true
            },
            vendor_company: {
                required: true
            },
            vendor_mobile: {
                required: true
            },
            vendor_location: {
                required: true
            },
            vendor_type: {
                required: true
            }
        },
        messages :{
            vendor_category : {
                required : '<small style="color:red">Please Select Category</small>'
            },
            vendor_name : {
                required : '<small style="color:red">Please Enter Name</small>'
            },
            vendor_company : {
                required : '<small style="color:red">Please Enter Company</small>'
            },
            vendor_mobile : {
                required : '<small style="color:red">Please Enter Mobile No</small>'
            },
            vendor_location : {
                required : '<small style="color:red">Please Enter Location</small>'
            },
            vendor_type : {
                required : '<small style="color:red">Please Enter Type</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "vendor_category")
            {
                error.appendTo($("#vendor_category_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#category_form').validate({
        rules: {
            category_name: {
                required: true
            }
        },
        messages :{
            category_name : {
                required : '<small style="color:red">Please Enter Category</small>'
            }
        }
    });

    $('#edit_category_form').validate({
        rules: {
            category_name: {
                required: true
            }
        },
        messages :{
            category_name : {
                required : '<small style="color:red">Please Enter Category</small>'
            }
        }
    });

    $('#material_category_form').validate({
        rules: {
            mcategory_name: {
                required: true
            }
        },
        messages :{
            mcategory_name : {
                required : '<small style="color:red">Please Enter Category</small>'
            }
        }
    });

    $('#edit_material_category_form').validate({
        rules: {
            mcategory_name: {
                required: true
            }
        },
        messages :{
            mcategory_name : {
                required : '<small style="color:red">Please Enter Category</small>'
            }
        }
    });

    $('#issue_category_form').validate({
        rules: {
            icategory_name: {
                required: true
            }
        },
        messages :{
            icategory_name : {
                required : '<small style="color:red">Please Enter Category</small>'
            }
        }
    });

    $('#edit_issue_category_form').validate({
        rules: {
            icategory_name: {
                required: true
            }
        },
        messages :{
            icategory_name : {
                required : '<small style="color:red">Please Enter Category</small>'
            }
        }
    });

    $('#labour_tracker_form').validate({
        rules: {
            ltracker_date: {
                required: true
            },
            ltracker_project: {
                required: true
            },
            ltracker_subcontractor: {
                required: true
            },
            ltracker_incharge: {
                required: true
            },
            ltracker_shift: {
                required: true
            },
            ltracker_in: {
                required: true
            },
            ltracker_out: {
                required: true
            },
            ltracker_thours: {
                required: true
            }
        },
        messages :{
            ltracker_date : {
                required : '<small style="color:red">Please Select Date</small>'
            },
            ltracker_project : {
                required : '<small style="color:red">Please Select Project</small>'
            },
            ltracker_subcontractor : {
                required : '<small style="color:red">Please Select Subcontractor</small>'
            },
            ltracker_incharge : {
                required : '<small style="color:red">Please Select Site Incharge</small>'
            },
            ltracker_shift : {
                required : '<small style="color:red">Please Select Shift</small>'
            },
            ltracker_in : {
                required : '<small style="color:red">Please Select In Time</small>'
            },
            ltracker_out : {
                required : '<small style="color:red">Please Select Out Time</small>'
            },
            ltracker_thours : {
                required : '<small style="color:red">Please Select Times</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "ltracker_project")
            {
                error.appendTo($("#ltracker_project_error"));
            }
            else if(element.attr("name") == "ltracker_subcontractor")
            {
                error.appendTo($("#ltracker_subcontractor_error"));
            }
            else if(element.attr("name") == "ltracker_incharge")
            {
                error.appendTo($("#ltracker_incharge_error"));
            }
            else if(element.attr("name") == "ltracker_shift")
            {
                error.appendTo($("#ltracker_shift_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#unit_form').validate({
        rules: {
            unit_name: {
                required: true
            }
        },
        messages :{
            unit_name : {
                required : '<small style="color:red">Please Enter Unit</small>'
            }
        }
    });

    $('#edit_unit_form').validate({
        rules: {
            unit_name: {
                required: true
            }
        },
        messages :{
            unit_name : {
                required : '<small style="color:red">Please Enter Unit</small>'
            }
        }
    });    

    $('#user_form').validate({
        rules: {
            user_role: {
                required: true
            },
            user_name: {
                required: true
            },
            user_lname: {
                required: true,
                usernamePattern: true
            },
            user_email: {
                required: true,
                email: true
            },
            user_phone: {
                required: true
            },
            user_password: {
                required: function () { return !document.getElementById('user_form').dataset.editUser; }
            }
        },
        messages :{
            user_role : {
                required : '<small style="color:red;position: relative;top: -20px;">Please Select Role</small>'
            },
            user_name : {
                required : '<small style="color:red">Please Enter Name</small>'
            },
            user_lname : {
                required : '<small style="color:red">Please Enter Username</small>',
                usernamePattern : '<small style="color:red">Please Enter Only Letters, Numbers, Underscores & Dots</small>'
            },
            user_email : {
                required : '<small style="color:red">Please Enter Email Address</small>',
                email : '<small style="color:red">Please Enter Valid Email Address</small>'
            },
            user_phone : {
                required : '<small style="color:red">Please Enter Mobile No</small>'
            },
            user_password : {
                required : '<small style="color:red">Please Enter Password</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "user_role")
            {
                error.appendTo($("#user_role_error"));
            }
            else if(element.attr("name") == "user_password")
            {
                error.appendTo($("#user_password_error"));
            }
            else
            {
                error.insertAfter(element);
            }
        }
    });

    $('#role_form').validate({
        rules: {
            role_name: {
                required: true
            }
        },
        messages :{
            role_name : {
                required : '<small style="color:red">Please Enter Role</small>'
            }
        },
        submitHandler: function (form) {
            
            Swal.fire({
                title: 'Loading...',
                text: 'Please Wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            form.submit();
        }
    });

    $('#settings_form').validate({
        rules: {
            settings_title: {
                required: true
            },
            settings_company: {
                required: true
            },
            settings_email: {
                required: true,
                email: true
            },
            settings_phone: {
                required: true
            },
            settings_address: {
                required: true
            }
        },
        messages :{
            settings_title : {
                required : '<small style="color:red">Please Enter Project Title</small>'
            },
            settings_company : {
                required : '<small style="color:red">Please Enter Company</small>'
            },
            settings_email : {
                required : '<small style="color:red">Please Enter Email</small>',
                email : '<small style="color:red">Please Enter Valid Email</small>'
            },
            settings_phone : {
                required : '<small style="color:red">Please Enter Phone</small>'
            },
            settings_address : {
                required : '<small style="color:red">Please Enter Address</small>'
            }
        }
    });

    $('#email_config_form').validate({
        rules: {
            settings_mailer: {
                required: true
            },
            settings_host: {
                required: true
            },
            settings_port: {
                required: true
            },
            settings_uname: {
                required: true
            },
            settings_pwd: {
                required: true
            },
            settings_encryption: {
                required: true
            },
            settings_faddress: {
                required: true
            },
            settings_fname: {
                required: true
            }
        },
        messages :{
            settings_mailer : {
                required : '<small style="color:red">Please Enter Mailer</small>'
            },
            settings_host : {
                required : '<small style="color:red">Please Enter Host</small>'
            },
            settings_port : {
                required : '<small style="color:red">Please Enter Port</small>'
            },
            settings_uname : {
                required : '<small style="color:red">Please Enter Username</small>'
            },
            settings_pwd : {
                required : '<small style="color:red">Please Enter Password</small>'
            },
            settings_encryption : {
                required : '<small style="color:red">Please Enter Encryption</small>'
            },
            settings_faddress : {
                required : '<small style="color:red">Please Enter From Address</small>'
            },
            settings_fname : {
                required : '<small style="color:red">Please Enter From Name</small>'
            }
        }
    });
    
});
