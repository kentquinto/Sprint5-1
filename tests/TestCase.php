<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\ClientRepository;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        if (Schema::hasTable('oauth_clients')) {
            app(ClientRepository::class)->createPersonalAccessGrantClient(
                'Personal Access Client', 'users'
            );
        }
    }

    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        // Reset the API guard cache so each request re-validates the Bearer token.
        // Only applies to API routes — web guard must stay intact for session-based tests.
        if (str_starts_with($uri, '/api/')) {
            Auth::forgetGuards();
        }

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }
}
