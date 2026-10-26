<?php

namespace App\Providers;

use App\Logging\PrivacyLogManager;
use App\Logging\RedactLogs;
use App\Security\RuntimeConfiguration;
use App\Security\SecurityPolicy;
use App\Security\TlsMySqlConnector;
use App\Services\ProjectContext;
use App\Services\RoleAccess;
use App\Services\WorkflowSchema;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('db.connector.mysql', fn () => new TlsMySqlConnector);
        $this->app->singleton('log', fn ($app) => new PrivacyLogManager($app));
        Facade::clearResolvedInstance('log');
        $this->app->booted(function () {
            $router = app('router');
            $routes = new RouteCollection;
            foreach ($router->getRoutes() as $route) {
                if (! preg_match('~^(?:_ignition|_debugbar|debug|test|admin-backdoor|seed-data)(?:/|$)~', $route->uri())) {
                    $routes->add($route);
                }
            }
            $router->setRoutes($routes);
        });
        $this->app->scoped(SecurityPolicy::class);
        $this->app->scoped(RoleAccess::class);
        $this->app->scoped(ProjectContext::class);
        $this->app->scoped(WorkflowSchema::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->environment(['local', 'testing'])) {
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
            'cors.allowed_origins' => config('security.frontend_origins', ['https://sitetrack.pro']),
            'cors.allowed_origins_patterns' => [],
            'cors.allowed_methods' => ['GET', 'HEAD', 'POST', 'OPTIONS'],
            'cors.allowed_headers' => ['Accept', 'Content-Type', 'X-CSRF-TOKEN', 'X-Requested-With'],
            'cors.exposed_headers' => ['X-Correlation-ID', 'Retry-After'],
            'cors.paths' => ['user/*', 'api/*', 'sanctum/csrf-cookie', 'forgot_password', 'reset-password', '/'],
            'datatables.error' => 'Unable to load entries. Please try again.',
        ]);
        if (! $this->app->environment('testing')) {
            config(['session.driver' => 'file']);
        }
        if (! $this->app->environment(['local', 'testing']) && in_array(parse_url(config('app.url'), PHP_URL_HOST), ['localhost', '127.0.0.1'], true)) {
            config(['app.url' => 'https://sitetrack.pro']);
        }
        app(RuntimeConfiguration::class)->enforce();
        foreach (array_keys(config('logging.channels', [])) as $channel) {
            config(['logging.channels.'.$channel.'.tap' => [RedactLogs::class]]);
        }
    }
}
