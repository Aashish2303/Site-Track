<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProjectController;
use App\Http\Controllers\User\DailyWorkController;
use App\Http\Controllers\User\DailyProgressController;
use App\Http\Controllers\User\BoqTrackerController;
use App\Http\Controllers\User\AssignMaterialController;
use App\Http\Controllers\User\ReturnMaterialController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\StockController;
use App\Http\Controllers\User\MaterialController;
use App\Http\Controllers\User\IndentController;
use App\Http\Controllers\User\LabourTrackerController;
use App\Http\Controllers\User\IssueController;
use App\Http\Controllers\User\SubcontractorLedgerController;
use App\Http\Controllers\User\VendorLedgerController;
use App\Http\Controllers\User\QmsController;
use App\Http\Controllers\User\ClientController;
use App\Http\Controllers\User\SubcontractorController;
use App\Http\Controllers\User\VendorController;
use App\Http\Controllers\User\CategoryController;
use App\Http\Controllers\User\MaterialCategoryController;
use App\Http\Controllers\User\IssueCategoryController;
use App\Http\Controllers\User\UnitController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\LogoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['common'])->group(function () {

    Route::middleware(['checkuserlogin'])->group(function () {

        Route::get('/', [LoginController::class, 'index'])->name('login');
        Route::post('/', [LoginController::class, 'index'])->middleware('throttle:login')->name('login.submit');
        Route::get('forgot_password', [LoginController::class, 'forgot_password'])->name('forgot_password');
        Route::post('forgot_password', [LoginController::class, 'forgot_password'])->middleware('throttle:recovery')->name('forgot_password.submit');
        Route::get('reset-password', [LoginController::class, 'reset_password'])->name('reset_password');
        Route::get('reset-password/{token}', fn (string $token) => redirect()->to(route('user.reset_password').'#token='.rawurlencode($token)))->where('token', '[a-f0-9]{64}');
        Route::post('reset-password', [LoginController::class, 'reset_password'])->middleware('throttle:recovery')->name('reset_password.submit');

    });

    Route::middleware(['checkuser','auth.session','checkpermission','privacyboundary','selectedproject','validateinput'])->group(function () {

        Route::prefix('user')->group(function () {

            Route::post('workspace/select', [\App\Http\Controllers\User\WorkspaceController::class, 'select'])->name('select_project');
            Route::post('workspace/leave', [\App\Http\Controllers\User\WorkspaceController::class, 'leave'])->name('leave_project');
            Route::get('workspace', [\App\Http\Controllers\User\WorkspaceController::class, 'show'])->name('project_workspace');
            Route::get('reports', [\App\Http\Controllers\User\ReportsController::class, 'index'])->name('reports');
            Route::get('site-materials', [\App\Http\Controllers\User\SiteMaterialController::class, 'index'])->name('site_materials');
            Route::post('site-materials/usage', [\App\Http\Controllers\User\SiteMaterialController::class, 'store'])->name('record_material_usage');
            Route::get('labour-payments', [\App\Http\Controllers\User\LabourPaymentController::class, 'index'])->name('labour_payments');
            Route::post('labour-payments', [\App\Http\Controllers\User\LabourPaymentController::class, 'store'])->name('record_labour_payment');
            Route::get('progress/{id}/review', [\App\Http\Controllers\User\ProgressReviewController::class, 'show'])->where('id', '(?:pr_[A-Za-z0-9_-]+|[0-9]+)')->name('progress_review');
            Route::post('progress/{id}/review', [\App\Http\Controllers\User\ProgressReviewController::class, 'review'])->where('id', '(?:pr_[A-Za-z0-9_-]+|[0-9]+)')->name('review_progress');
            Route::get('progress/{id}/photos/{photo}', [\App\Http\Controllers\User\ProgressReviewController::class, 'photo'])->where(['id' => '(?:pr_[A-Za-z0-9_-]+|[0-9]+)', 'photo' => '(?:pr_[A-Za-z0-9_-]+|[0-9]+)'])->name('progress_photo');

            //Dashboard

            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

            //Project

            Route::get('projects', [ProjectController::class, 'index'])->name('projects');
            Route::post('get_projects', [ProjectController::class, 'get_projects'])->name('get_projects');
            Route::match(['get','post'],'add_project', [ProjectController::class, 'add_project'])->name('add_project');
            Route::match(['get','post'],'edit_project/{id}', [ProjectController::class, 'edit_project'])->name('edit_project');
            Route::get('view_project/{id}', [ProjectController::class, 'view_project'])->name('view_project');
            Route::post('project_status', [ProjectController::class, 'project_status'])->name('project_status');
            Route::post('add_project_status', [ProjectController::class, 'add_project_status'])->name('add_project_status');

            //Client

            Route::get('clients', [ClientController::class, 'index'])->name('clients');
            Route::post('get_clients', [ClientController::class, 'get_clients'])->name('get_clients');
            Route::match(['get','post'],'add_client', [ClientController::class, 'add_client'])->name('add_client');
            Route::match(['get','post'],'edit_client/{id}', [ClientController::class, 'edit_client'])->name('edit_client');
            Route::post('client_status/{id}/{status}', [ClientController::class, 'client_status'])->name('client_status');
            
            //Daily Work

            Route::get('daily_works', [DailyWorkController::class, 'index'])->name('daily_works');
            Route::post('get_daily_works', [DailyWorkController::class, 'get_daily_works'])->name('get_daily_works');
            Route::match(['get','post'],'add_daily_work', [DailyWorkController::class, 'add_daily_work'])->name('add_daily_work');
            Route::match(['get','post'],'edit_daily_work/{id}', [DailyWorkController::class, 'edit_daily_work'])->name('edit_daily_work');
            Route::post('daily_work_status', [DailyWorkController::class, 'daily_work_status'])->name('daily_work_status');
            Route::post('add_daily_work_status', [DailyWorkController::class, 'add_daily_work_status'])->name('add_daily_work_status');
            Route::get('works_download_excel', [DailyWorkController::class, 'works_download_excel'])->name('works_download_excel');

            //Daily Progress

            Route::get('daily_progress', [DailyProgressController::class, 'index'])->name('daily_progress');
            Route::post('get_daily_progress', [DailyProgressController::class, 'get_daily_progress'])->name('get_daily_progress');
            Route::match(['get','post'],'add_daily_progress', [DailyProgressController::class, 'add_daily_progress'])->name('add_daily_progress');
            Route::post('select_boq', [DailyProgressController::class, 'select_boq'])->name('select_boq');
            Route::match(['get','post'],'edit_daily_progress/{id}', [DailyProgressController::class, 'edit_daily_progress'])->name('edit_daily_progress');
            Route::post('daily_progress_status', [DailyProgressController::class, 'daily_progress_status'])->name('daily_progress_status');
            Route::post('add_daily_progress_status', [DailyProgressController::class, 'add_daily_progress_status'])->name('add_daily_progress_status');
            Route::get('progress_download_excel', [DailyProgressController::class, 'progress_download_excel'])->name('progress_download_excel');

            //BOQ Tracker

            Route::get('boq_tracker', [BoqTrackerController::class, 'index'])->name('boq_tracker');
            Route::post('add_boq_item', [BoqTrackerController::class, 'add_boq_item'])->name('add_boq_item');
            Route::post('get_boq_project_details', [BoqTrackerController::class, 'get_boq_project_details'])->name('get_boq_project_details');
            Route::post('edit_boq_item', [BoqTrackerController::class, 'edit_boq_item'])->name('edit_boq_item');
            Route::post('get_boq_item_details', [BoqTrackerController::class, 'get_boq_item_details'])->name('get_boq_item_details');

            //Assign Material

            Route::get('assign_materials', [AssignMaterialController::class, 'index'])->name('assign_materials');
            Route::post('get_assign_materials', [AssignMaterialController::class, 'get_assign_materials'])->name('get_assign_materials');
            Route::match(['get','post'],'add_assign_material', [AssignMaterialController::class, 'add_assign_material'])->name('add_assign_material');
            Route::post('select_materials', [AssignMaterialController::class, 'select_materials'])->name('select_materials');
            Route::post('select_material_stock', [AssignMaterialController::class, 'select_material_stock'])->name('select_material_stock');
            
            //Return Material

            Route::get('return_materials', [ReturnMaterialController::class, 'index'])->name('return_materials');
            Route::post('get_return_materials', [ReturnMaterialController::class, 'get_return_materials'])->name('get_return_materials');
            Route::match(['get','post'],'add_return_material', [ReturnMaterialController::class, 'add_return_material'])->name('add_return_material');
            
            //Order

            Route::get('orders', [OrderController::class, 'index'])->name('orders');
            Route::post('get_orders', [OrderController::class, 'get_orders'])->name('get_orders');
            Route::match(['get','post'],'add_order', [OrderController::class, 'add_order'])->name('add_order');
            Route::post('select_material_unit', [OrderController::class, 'select_material_unit'])->name('select_material_unit');
            Route::match(['get','post'],'edit_order/{id}', [OrderController::class, 'edit_order'])->name('edit_order');
            Route::post('order_pay_status', [OrderController::class, 'order_pay_status'])->name('order_pay_status');
            Route::post('add_order_pay_status', [OrderController::class, 'add_order_pay_status'])->name('add_order_pay_status');
            Route::get('order_delivery/{id}', [OrderController::class, 'order_delivery'])->name('order_delivery');
            Route::post('add_order_delivery/{id}', [OrderController::class, 'add_order_delivery'])->name('add_order_delivery');

            //Stock

            Route::get('stocks', [StockController::class, 'index'])->name('stocks');
            Route::post('get_stocks', [StockController::class, 'get_stocks'])->name('get_stocks');

            //Material

            Route::get('materials', [MaterialController::class, 'index'])->name('materials');
            Route::post('get_materials', [MaterialController::class, 'get_materials'])->name('get_materials');
            Route::match(['get','post'],'add_material', [MaterialController::class, 'add_material'])->name('add_material');
            Route::match(['get','post'],'edit_material/{id}', [MaterialController::class, 'edit_material'])->name('edit_material');
            Route::post('material_status/{id}/{status}', [MaterialController::class, 'material_status'])->name('material_status');

            //Material Category

            Route::get('material_category', [MaterialCategoryController::class, 'index'])->name('material_category');
            Route::post('get_material_category', [MaterialCategoryController::class, 'get_material_category'])->name('get_material_category');
            Route::post('add_material_category', [MaterialCategoryController::class, 'add_material_category'])->name('add_material_category');
            Route::post('edit_material_category', [MaterialCategoryController::class, 'edit_material_category'])->name('edit_material_category');
            Route::post('get_material_category_details', [MaterialCategoryController::class, 'get_material_category_details'])->name('get_material_category_details');
            Route::post('material_category_status/{id}/{status}', [MaterialCategoryController::class, 'material_category_status'])->name('material_category_status');

            //Indent

            Route::get('indents', [IndentController::class, 'index'])->name('indents');
            Route::post('get_indents', [IndentController::class, 'get_indents'])->name('get_indents');
            Route::match(['get','post'],'add_indent', [IndentController::class, 'add_indent'])->name('add_indent');
            Route::match(['get','post'],'edit_indent/{id}', [IndentController::class, 'edit_indent'])->name('edit_indent');
            Route::get('download_indent/{id}', [IndentController::class, 'download_indent'])->name('download_indent');
            Route::get('indent_items/{id}', [IndentController::class, 'indent_items'])->name('indent_items');
            Route::post('indent_item_status', [IndentController::class, 'indent_item_status'])->name('indent_item_status');
            Route::post('add_indent_item_status/{id}', [IndentController::class, 'add_indent_item_status'])->name('add_indent_item_status');
            Route::get('indent_quotations/{id}', [IndentController::class, 'indent_quotations'])->name('indent_quotations');
            Route::post('add_quotation/{id}', [IndentController::class, 'add_quotation'])->name('add_quotation');
            Route::post('reject_quotations/{id}', [IndentController::class, 'reject_quotations'])->name('reject_quotations');
            Route::post('status_quotation/{id}/{status}', [IndentController::class, 'status_quotation'])->name('status_quotation');

            //Labour Tracker

            Route::get('labour_trackers', [LabourTrackerController::class, 'index'])->name('labour_trackers');
            Route::post('get_labour_trackers', [LabourTrackerController::class, 'get_labour_trackers'])->name('get_labour_trackers');
            Route::match(['get','post'],'add_labour_tracker', [LabourTrackerController::class, 'add_labour_tracker'])->name('add_labour_tracker');
            Route::match(['get','post'],'edit_labour_tracker/{id}', [LabourTrackerController::class, 'edit_labour_tracker'])->name('edit_labour_tracker');
            Route::get('labours_download_excel', [LabourTrackerController::class, 'labours_download_excel'])->name('labours_download_excel');

            //Issue

            Route::get('issues', [IssueController::class, 'index'])->name('issues');
            Route::post('get_issues', [IssueController::class, 'get_issues'])->name('get_issues');
            Route::match(['get','post'],'add_issue', [IssueController::class, 'add_issue'])->name('add_issue');
            Route::match(['get','post'],'edit_issue/{id}', [IssueController::class, 'edit_issue'])->name('edit_issue');
            Route::post('issue_status', [IssueController::class, 'issue_status'])->name('issue_status');
            Route::post('add_issue_status', [IssueController::class, 'add_issue_status'])->name('add_issue_status');
            Route::get('issues_download_excel', [IssueController::class, 'issues_download_excel'])->name('issues_download_excel');

            //Issue Category

            Route::get('issue_category', [IssueCategoryController::class, 'index'])->name('issue_category');
            Route::post('get_issue_category', [IssueCategoryController::class, 'get_issue_category'])->name('get_issue_category');
            Route::post('add_issue_category', [IssueCategoryController::class, 'add_issue_category'])->name('add_issue_category');
            Route::post('edit_issue_category', [IssueCategoryController::class, 'edit_issue_category'])->name('edit_issue_category');
            Route::post('get_issue_category_details', [IssueCategoryController::class, 'get_issue_category_details'])->name('get_issue_category_details');
            Route::post('issue_category_status/{id}/{status}', [IssueCategoryController::class, 'issue_category_status'])->name('issue_category_status');

            //Subcontractor Ledger

            Route::get('subcontractor_ledger', [SubcontractorLedgerController::class, 'index'])->name('subcontractor_ledger');
            Route::post('get_subcontractor_ledger', [SubcontractorLedgerController::class, 'get_subcontractor_ledger'])->name('get_subcontractor_ledger');
            Route::match(['get','post'],'add_subcontractor_ledger', [SubcontractorLedgerController::class, 'add_subcontractor_ledger'])->name('add_subcontractor_ledger');
            Route::get('subcontractor_ledger_download_excel', [SubcontractorLedgerController::class, 'subcontractor_ledger_download_excel'])->name('subcontractor_ledger_download_excel');

            //Vendor Ledger

            Route::get('vendor_ledger', [VendorLedgerController::class, 'index'])->name('vendor_ledger');
            Route::post('get_vendor_ledger', [VendorLedgerController::class, 'get_vendor_ledger'])->name('get_vendor_ledger');
            Route::match(['get','post'],'add_vendor_ledger', [VendorLedgerController::class, 'add_vendor_ledger'])->name('add_vendor_ledger');
            Route::get('vendor_ledger_download_excel', [VendorLedgerController::class, 'vendor_ledger_download_excel'])->name('vendor_ledger_download_excel');

            //Vendor

            Route::get('vendors', [VendorController::class, 'index'])->name('vendors');
            Route::post('get_vendors', [VendorController::class, 'get_vendors'])->name('get_vendors');
            Route::match(['get','post'],'add_vendor', [VendorController::class, 'add_vendor'])->name('add_vendor');
            Route::match(['get','post'],'edit_vendor/{id}', [VendorController::class, 'edit_vendor'])->name('edit_vendor');
            Route::post('vendor_status/{id}/{status}', [VendorController::class, 'vendor_status'])->name('vendor_status');
            Route::get('vendors_download_excel', [VendorController::class, 'vendors_download_excel'])->name('vendors_download_excel');

            //Subcontractor

            Route::get('subcontractors', [SubcontractorController::class, 'index'])->name('subcontractors');
            Route::post('get_subcontractors', [SubcontractorController::class, 'get_subcontractors'])->name('get_subcontractors');
            Route::match(['get','post'],'add_subcontractor', [SubcontractorController::class, 'add_subcontractor'])->name('add_subcontractor');
            Route::match(['get','post'],'edit_subcontractor/{id}', [SubcontractorController::class, 'edit_subcontractor'])->name('edit_subcontractor');
            Route::post('subcontractor_status/{id}/{status}', [SubcontractorController::class, 'subcontractor_status'])->name('subcontractor_status');

            //Category

            Route::get('category', [CategoryController::class, 'index'])->name('category');
            Route::post('get_category', [CategoryController::class, 'get_category'])->name('get_category');
            Route::post('add_category', [CategoryController::class, 'add_category'])->name('add_category');
            Route::post('edit_category', [CategoryController::class, 'edit_category'])->name('edit_category');
            Route::post('get_category_details', [CategoryController::class, 'get_category_details'])->name('get_category_details');
            Route::post('category_status/{id}/{status}', [CategoryController::class, 'category_status'])->name('category_status');

            //Unit

            Route::get('units', [UnitController::class, 'index'])->name('units');
            Route::post('get_units', [UnitController::class, 'get_units'])->name('get_units');
            Route::post('add_unit', [UnitController::class, 'add_unit'])->name('add_unit');
            Route::post('edit_unit', [UnitController::class, 'edit_unit'])->name('edit_unit');
            Route::post('get_unit_details', [UnitController::class, 'get_unit_details'])->name('get_unit_details');
            Route::post('unit_status/{id}/{status}', [UnitController::class, 'unit_status'])->name('unit_status');
            
            //QMS

            Route::get('qms', [QmsController::class, 'index'])->name('qms');
            Route::get('qms_download_excel', [QmsController::class, 'qms_download_excel'])->name('qms_download_excel');

            //User

            Route::get('users', [UserController::class, 'index'])->name('users');
            Route::post('get_users', [UserController::class, 'get_users'])->name('get_users');
            Route::match(['get','post'],'add_user', [UserController::class, 'add_user'])->name('add_user');
            Route::match(['get','post'],'edit_user/{id}', [UserController::class, 'edit_user'])->name('edit_user');
            Route::post('user_status/{id}/{status}', [UserController::class, 'user_status'])->name('user_status');

            //Role

            Route::get('roles', [RoleController::class, 'index'])->name('roles');
            Route::post('get_roles', [RoleController::class, 'get_roles'])->name('get_roles');
            Route::match(['get','post'],'add_role', [RoleController::class, 'add_role'])->name('add_role');
            Route::match(['get','post'],'edit_role/{id}', [RoleController::class, 'edit_role'])->name('edit_role');
            Route::post('role_status/{id}/{status}', [RoleController::class, 'role_status'])->name('role_status');

            //Settings

            Route::match(['get','post'],'settings', [SettingsController::class, 'index'])->name('settings');
            Route::match(['get','post'],'email_config_settings', [SettingsController::class, 'email_config_settings'])->name('email_config_settings');

            Route::get('files/{kind}/{reference}', [\App\Http\Controllers\User\PrivateFileController::class, 'show'])->name('private_file');

            Route::post('account/delete', [\App\Http\Controllers\User\AccountDeletionController::class, 'destroy'])->middleware('throttle:password-change')->name('delete_account');

            //Profile

            Route::match(['get','post'],'profile', [ProfileController::class, 'index'])->name('profile');
            Route::match(['get','post'],'change_password', [ProfileController::class, 'change_password'])->middleware('throttle:password-change')->name('change_password');

            //Logout

            Route::get('logout', [LogoutController::class, 'index'])->name('logout');

        });

    });

});
