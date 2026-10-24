<?php

namespace Tests\Feature;

use App\Security\RuntimeConfiguration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecurityReadinessTest extends TestCase
{
    private function productionConfiguration(): array
    {
        $c = config()->all();
        $c['app']['env'] = 'production';
        $c['app']['url'] = 'https://sitetrack.pro';
        $c['cache']['default'] = 'file';
        $c['database']['connections']['mysql'] = array_replace($c['database']['connections']['mysql'], [
            'url' => null, 'username' => 'application_service', 'password' => Str::random(40), 'database' => 'app', 'host' => 'db.example.invalid',
        ]);

        return $c;
    }

    public function test_missing_auth_or_database_configuration_is_rejected_without_printing_values(): void
    {
        $checker = new RuntimeConfiguration;
        $c = $this->productionConfiguration();
        $this->assertSame([], $checker->problems($c));
        $c['app']['key'] = '';
        $c['database']['connections']['mysql']['username'] = '';
        $c['database']['connections']['mysql']['password'] = '';
        $c['database']['connections']['mysql']['database'] = '';
        $errors = $checker->problems($c);
        foreach (['APP_KEY', 'DB_USERNAME / DATABASE_URL', 'DB_PASSWORD', 'DB_DATABASE / DATABASE_URL'] as $field) {
            $this->assertArrayHasKey($field, $errors);
        }
        $c = $this->productionConfiguration();
        $c['database']['connections']['mysql']['username'] = 'root';
        $this->assertArrayHasKey('DB_USERNAME', $checker->problems($c));
    }

    public function test_database_url_and_conditional_provider_keys_are_validated(): void
    {
        $c = $this->productionConfiguration();
        $c['database']['connections']['mysql']['url'] = 'mysql://application_service:'.Str::random(32).'@db.example.invalid/app';
        $c['database']['connections']['mysql']['username'] = null;
        $c['database']['connections']['mysql']['database'] = null;
        $this->assertSame([], (new RuntimeConfiguration)->problems($c));
        $c['mail']['default'] = 'postmark';
        $c['services']['postmark']['token'] = null;
        $this->assertArrayHasKey('POSTMARK_TOKEN', (new RuntimeConfiguration)->problems($c));
        $c['services']['postmark']['token'] = Str::random(40);
        $this->assertSame([], (new RuntimeConfiguration)->problems($c));
        $c['broadcasting']['default'] = 'pusher';
        $c['broadcasting']['connections']['pusher']['secret'] = null;
        $this->assertArrayHasKey('PUSHER_APP_SECRET', (new RuntimeConfiguration)->problems($c));
        $c['queue']['default'] = 'sqs';
        $c['queue']['connections']['sqs']['key'] = null;
        $this->assertArrayHasKey('AWS_ACCESS_KEY_ID', (new RuntimeConfiguration)->problems($c));
        $c['cache']['default'] = 'array';
        $this->assertArrayHasKey('CACHE_DRIVER', (new RuntimeConfiguration)->problems($c));
    }

    public function test_every_application_response_has_security_headers_and_a_fresh_nonce(): void
    {
        $last = null;
        foreach (['/', '/page-that-does-not-exist', '/user/profile'] as $url) {
            $r = $this->get($url)->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('X-Frame-Options', 'DENY')
                ->assertHeader('Strict-Transport-Security', 'max-age=31536000')->assertHeader('X-Correlation-ID');
            $policy = $r->headers->get('Content-Security-Policy');
            $this->assertStringContainsString("script-src 'self' 'nonce-", $policy);
            $this->assertStringContainsString("script-src-attr 'none'", $policy);
            $this->assertStringNotContainsString("script-src 'unsafe-inline'", $policy);
            $this->assertStringNotContainsString("'unsafe-eval'", $policy);
            preg_match("/'nonce-([^']+)'/", $policy, $match);
            $this->assertNotSame($last, $match[1]);
            $last = $match[1];
        }
    }

    public function test_inline_scripts_are_nonces_and_inline_event_attributes_are_absent(): void
    {
        $response = $this->get('/')->assertOk();
        preg_match("/'nonce-([^']+)'/", $response->headers->get('Content-Security-Policy'), $match);
        $dom = new \DOMDocument;
        @$dom->loadHTML($response->getContent());
        foreach ($dom->getElementsByTagName('script') as $script) {
            if (! $script->hasAttribute('src')) {
                $this->assertSame($match[1], $script->getAttribute('nonce'));
            }
        }
        $this->assertDoesNotMatchRegularExpression('/\bon(?:click|change|error|load|submit)\s*=/', $response->getContent());
    }

    public function test_errors_are_generic_and_log_the_same_correlation_id_without_sensitive_arguments(): void
    {
        $secret = 'private-'.Str::random(30);
        Route::middleware('web')->get('/security-error-fixture', fn () => throw new \RuntimeException('SQL SELECT email password path /srv/private '.$secret));
        Log::spy();
        $response = $this->getJson('/security-error-fixture')->assertStatus(500)->assertJsonPath('message', 'Unable to complete this request. Please try again.')
            ->assertDontSee($secret)->assertDontSee('SELECT')->assertDontSee('/srv/private')->assertDontSee('RuntimeException');
        $id = $response->json('correlation_id');
        $response->assertHeader('X-Correlation-ID', $id);
        Log::shouldHaveReceived('error')->with('app.error', \Mockery::on(function ($context) use ($id, $secret) {
            return $context['correlation_id'] === $id && isset($context['diagnostics']) && ! str_contains(json_encode($context), $secret);
        }));
        $this->getJson('/unknown-page')->assertNotFound()->assertJsonPath('message', 'The requested page or record is unavailable.')->assertJsonStructure(['correlation_id']);
        $this->postJson('/', [])->assertUnprocessable()->assertJsonStructure(['message', 'correlation_id', 'errors']);
    }

    public function test_login_ip_and_recovery_ip_limits_cannot_be_bypassed_by_rotating_identifiers(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/', ['user_lname' => 'missing-'.Str::random(8), 'user_password' => Str::random(16)])->assertRedirect();
        }
        $this->postJson('/', ['user_lname' => 'different-account', 'user_password' => Str::random(16)])->assertStatus(429)->assertHeader('Retry-After')->assertJsonStructure(['correlation_id']);
        for ($i = 0; $i < 3; $i++) {
            $this->post('/forgot_password', ['user_email' => 'missing-'.Str::random(8).'@example.test'])->assertRedirect();
        }
        $this->postJson('/forgot_password', ['user_email' => 'different@example.test'])->assertStatus(429)->assertHeader('Retry-After')->assertJsonStructure(['correlation_id']);
    }

    public function test_cors_grants_only_the_configured_frontend_origin(): void
    {
        $this->withHeaders(['Origin' => 'https://sitetrack.pro', 'Access-Control-Request-Method' => 'POST', 'Access-Control-Request-Headers' => 'content-type'])
            ->options('/forgot_password')->assertSuccessful()->assertHeader('Access-Control-Allow-Origin', 'https://sitetrack.pro')->assertHeader('X-Frame-Options', 'DENY');
        $this->withHeader('Origin', 'https://untrusted.example')->options('/forgot_password')->assertHeaderMissing('Access-Control-Allow-Origin');
        $c = $this->productionConfiguration();
        $c['cors']['allowed_origins'] = ['*'];
        $this->assertArrayHasKey('CORS_ALLOWED_ORIGINS', (new RuntimeConfiguration)->problems($c));
    }

    public function test_debug_or_seed_routes_are_not_exposed(): void
    {
        foreach (['/test', '/debug', '/admin-backdoor', '/seed-data', '/phpinfo', '/_ignition/health-check', '/_debugbar/open'] as $url) {
            $this->get($url)->assertNotFound()->assertHeader('X-Correlation-ID');
        }
        $this->assertStringContainsString('APP_DEBUG=false', file_get_contents(base_path('.env.example')));
    }
}
