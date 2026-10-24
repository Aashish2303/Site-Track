<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Guards make this additive migration resumable after an interrupted MySQL DDL operation.
        if (!Schema::hasTable('progress_reviews')) {
            Schema::create('progress_reviews', function (Blueprint $t) {
                $t->unsignedBigInteger('progress_id')->primary();
                $t->string('state', 16)->default('pending')->index();
                $t->decimal('approved_qty', 16, 4)->default(0);
                $t->unsignedInteger('revision')->default(1);
                $t->unsignedBigInteger('reviewer_id')->nullable();
                $t->dateTime('reviewed_at')->nullable();
                $t->text('note')->nullable();
            });
        }
        if (!Schema::hasTable('progress_events')) {
            Schema::create('progress_events', function (Blueprint $t) {
                $t->id(); $t->unsignedBigInteger('progress_id')->index();
                $t->unsignedBigInteger('actor_id'); $t->string('action', 24);
                $t->unsignedInteger('revision');
                $t->json('before_data')->nullable(); $t->json('after_data');
                $t->text('note')->nullable(); $t->dateTime('created_at');
            });
        }
        if (!Schema::hasTable('progress_photos')) {
            Schema::create('progress_photos', function (Blueprint $t) {
                $t->id(); $t->unsignedBigInteger('progress_id')->index();
                $t->string('path'); $t->string('mime', 40);
                $t->unsignedBigInteger('uploaded_by'); $t->dateTime('uploaded_at');
            });
        }
        if (!Schema::hasTable('site_material_usage')) {
            Schema::create('site_material_usage', function (Blueprint $t) {
                $t->id(); $t->unsignedBigInteger('project_id');
                $t->unsignedBigInteger('material_id'); $t->unsignedBigInteger('unit_id');
                $t->date('used_on'); $t->decimal('quantity', 16, 4);
                $t->decimal('unit_rate', 16, 4)->nullable();
                $t->text('notes')->nullable(); $t->uuid('submission_key')->unique();
                $t->unsignedBigInteger('created_by'); $t->dateTime('created_at');
                $t->index(['project_id', 'material_id', 'used_on']);
            });
        }
        if (!Schema::hasTable('labour_payments')) {
            Schema::create('labour_payments', function (Blueprint $t) {
                $t->id(); $t->unsignedBigInteger('project_id');
                $t->unsignedBigInteger('subcontractor_id'); $t->unsignedBigInteger('category_id');
                $t->date('paid_on'); $t->string('type', 16);
                $t->decimal('amount', 16, 2); $t->string('reference', 120);
                $t->text('notes')->nullable(); $t->uuid('submission_key')->unique();
                $t->unsignedBigInteger('created_by'); $t->dateTime('created_at');
                $t->index(['project_id', 'subcontractor_id', 'category_id', 'paid_on'], 'labour_payment_account');
            });
        }
        foreach (['lworker_overtime_hours' => [8, 2], 'lworker_overtime_rate' => [16, 2]] as $name => $precision) {
            if (!Schema::hasColumn('labour_workers', $name)) {
                Schema::table('labour_workers', function (Blueprint $t) use ($name, $precision) {
                    $t->decimal($name, ...$precision)->default(0);
                });
            }
        }

        // Historic reports remain reported, never silently promoted to engineer-approved work.
        DB::table('daily_progress')->orderBy('dprogress_id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                DB::table('progress_reviews')->insertOrIgnore([
                    'progress_id' => $row->dprogress_id, 'state' => 'legacy',
                    'approved_qty' => 0, 'revision' => 1,
                ]);
            }
        }, 'dprogress_id');

        $now = now()->format('Y-m-d H:i:s');
        $permissions = [
            'user.reports' => [19, 'Site reports'],
            'user.reports_export' => [19, 'Export site reports'],
            'user.review_progress' => [4, 'Approve reported quantities'],
            'user.site_material_usage' => [24, 'Record site consumption'],
            'user.labour_payments' => [10, 'View labour payment review'],
            'user.record_labour_payment' => [10, 'Record labour payments and advances'],
        ];
        foreach ($permissions as $slug => [$module, $name]) {
            $permission = DB::table('module_permissions')->where('mpermission_slug', $slug)->first();
            $id = $permission?->mpermission_id ?? DB::table('module_permissions')->insertGetId([
                'mpermission_module' => $module, 'mpermission_name' => $name, 'mpermission_slug' => $slug,
                'mpermission_added_on' => $now, 'mpermission_added_by' => 1,
                'mpermission_updated_on' => $now, 'mpermission_updated_by' => 1, 'mpermission_status' => 1,
            ]);
            if (!DB::table('role_permissions')->where('rpermission_role', 1)->where('rpermission_permission', $id)->exists()) {
                DB::table('role_permissions')->insert([
                    'rpermission_role' => 1, 'rpermission_permission' => $id,
                    'rpermission_added_on' => $now, 'rpermission_added_by' => 1,
                    'rpermission_updated_on' => $now, 'rpermission_updated_by' => 1, 'rpermission_status' => 1,
                ]);
            }
        }
        if (!Schema::hasTable('site_workflow_installations')) {
            Schema::create('site_workflow_installations', function (Blueprint $t) {
                $t->unsignedInteger('version')->primary(); $t->dateTime('installed_at');
            });
        }
        DB::table('site_workflow_installations')->insertOrIgnore(['version' => 1, 'installed_at' => $now]);
    }

    public function down(): void
    {
        throw new LogicException('Preserve approval history and financial records. Use a forward migration to change this schema.');
    }
};
