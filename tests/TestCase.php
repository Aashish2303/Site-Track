<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function actingAs(Authenticatable $user, $guard = null)
    {
        parent::actingAs($user, $guard);

        // Simulate the session established by the real login controller.
        return $this->withSession([
            Auth::guard($guard)->getName() => $user->getAuthIdentifier(),
            'password_hash_web' => $user->getAuthPassword(),
        ]);
    }
}
