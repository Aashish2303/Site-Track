<?php

namespace App\Providers;

use App\Logging\PrivacyLogManager;
use App\Logging\RedactLogs;
use App\Services\ProjectContext;
use App\Services\RoleAccess;
use App\Services\WorkflowSchema;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('log', fn ($app) => new PrivacyLogManager($app));
        Facade::clearResolvedInstance('log');
        $this->app->scoped(RoleAccess::class);
        $this->app->scoped(ProjectContext::class);
        $this->app->scoped(WorkflowSchema::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            config(['app.debug' => false]);
        }
        // Apply privacy invariants even when a host still has an older cached configuration.
        config([
            'filesystems.disks.personal' => ['driver' => 'local', 'root' => storage_path('app/private/personal'), 'visibility' => 'private', 'throw' => true],
            'filesystems.disks.evidence' => ['driver' => 'local', 'root' => storage_path('app/private'), 'throw' => true],
            'session.encrypt' => true, 'session.http_only' => true, 'session.same_site' => 'lax',
            'session.secure' => $this->app->environment('production') || parse_url(config('app.url'), PHP_URL_SCHEME) === 'https',
            'logging.default' => 'daily', 'logging.channels.daily.days' => 7,
            'logging.channels.daily.permission' => 0600,
            'mail.mailers.log.transport' => 'array',
            'excel.temporary_files.remote_disk' => null,
            'excel.temporary_files.local_permissions' => ['dir' => 0700, 'file' => 0600],
            'datatables.error' => 'Unable to load entries. Please try again.',
        ]);
        if (! $this->app->environment('testing')) {
            config(['session.driver' => 'file']);
        }
        foreach (array_keys(config('logging.channels', [])) as $channel) {
            config(['logging.channels.'.$channel.'.tap' => [RedactLogs::class]]);
        }
    }
}
