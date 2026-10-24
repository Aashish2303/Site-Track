<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(\App\Services\ProjectContext::class);
        $this->app->scoped(\App\Services\WorkflowSchema::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Existing hosts may still have an older cached filesystem configuration during rollout.
        if (!config('filesystems.disks.evidence')) {
            config(['filesystems.disks.evidence' => [
                'driver' => 'local', 'root' => storage_path('app/private'), 'throw' => true,
            ]]);
        }
    }
}
