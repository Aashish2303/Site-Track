<?php

// Output fields required by each actual table, not the complete Eloquent record.
return [
    'user' => [
        'get_assign_materials' => ['DT_RowIndex', 'assign_date', 'project_name', 'category_name', 'material_name', 'unit_name', 'amaterial_qty'],
        'get_category' => ['DT_RowIndex', 'category_name', 'action'],
        'get_clients' => ['DT_RowIndex', 'client_name', 'client_email', 'client_phone', 'client_address', 'action'],
        'get_daily_progress' => ['DT_RowIndex', 'work_date', 'project_name', 'category_name', 'dprogress_description', 'boq_name', 'dprogress_qty', 'approved_quantity', 'verification', 'progress', 'carry_forward', 'status', 'action', 'DT_RowClass'],
        'get_daily_works' => ['DT_RowIndex', 'work_date', 'project_name', 'category_name', 'dwork_description', 'dwork_location', 'dwork_qty', 'carry_forward', 'status', 'action', 'DT_RowClass'],
        'get_indents' => ['DT_RowIndex', 'indent_date', 'project_name', 'engineer_name', 'indent_notes', 'indent_napproval', 'total_quotations', 'urgency', 'status', 'action'],
        'get_issue_category' => ['DT_RowIndex', 'icategory_name', 'action'],
        'get_issues' => ['DT_RowIndex', 'issue_date', 'project_name', 'issue_title', 'issue_description', 'category_name', 'assigned_to', 'raised_by', 'priority', 'status', 'issue_sremarks', 'action'],
        'get_labour_trackers' => ['DT_RowIndex', 'tracker_date', 'project_name', 'categories', 'mf', 'time_period', 'ltracker_thours', 'shift', 'total_cost', 'action'],
        'get_material_category' => ['DT_RowIndex', 'mcategory_name', 'action'],
        'get_materials' => ['DT_RowIndex', 'category_name', 'material_name', 'unit_name', 'material_hsn', 'action'],
        'get_orders' => ['DT_RowIndex', 'order_date', 'vendor_name', 'material_name', 'unit_name', 'order_qty', 'delivered_qty', 'pending_qty', 'order_rate', 'pay_status', 'delivery_status', 'action'],
        'get_projects' => ['DT_RowIndex', 'project_name', 'client_name', 'engineer_name', 'period', 'budget', 'boq_items', 'progress', 'status', 'action'],
        'get_return_materials' => ['DT_RowIndex', 'return_date', 'project_name', 'category_name', 'material_name', 'unit_name', 'rmaterial_qty'],
        'get_roles' => ['DT_RowIndex', 'role_name', 'action'],
        'get_stocks' => ['DT_RowIndex', 'material_name', 'category_name', 'unit_name', 'stock_qty'],
        'get_subcontractor_ledger' => ['DT_RowIndex', 'ledger_date', 'subcontractor_name', 'project_name', 'type_name', 'sledger_wpno', 'sledger_description', 'total_amount', 'sledger_invoice', 'paid_amount', 'balance', 'sledger_remarks'],
        'get_subcontractors' => ['DT_RowIndex', 'subcontractor_name', 'subcontractor_email', 'subcontractor_phone', 'subcontractor_address', 'action'],
        'get_units' => ['DT_RowIndex', 'unit_name', 'action'],
        'get_users' => ['DT_RowIndex', 'user_name', 'role_name', 'user_lname', 'user_email', 'user_phone', 'action'],
        'get_vendor_ledger' => ['DT_RowIndex', 'ledger_date', 'vendor_name', 'project_name', 'type_name', 'vledger_pono', 'vledger_description', 'vledger_po_total', 'vledger_invoice', 'vledger_paid', 'balance', 'vledger_remarks'],
        'get_vendors' => ['DT_RowIndex', 'category_name', 'vendor_name', 'vendor_company', 'vendor_mobile', 'vendor_location', 'action'],
    ],
];
