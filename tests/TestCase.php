<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh', [
            '--database' => 'mysql',
            '--path' => 'database/migrations',
            '--realpath' => true,
        ]);

        Artisan::call('migrate', [
            '--database' => 'mysql',
            '--path' => 'database/migrations/unit_kerja',
            '--realpath' => true,
        ]);

        Artisan::call('migrate', [
            '--database' => 'mysql',
            '--path' => 'database/migrations/ticketing',
            '--realpath' => true,
        ]);

        Artisan::call('migrate:fresh', [
            '--database' => 'ssd',
            '--path' => 'database/migrations/ssd',
            '--realpath' => true,
        ]);

        Artisan::call('migrate:fresh', [
            '--database' => 'chatbot',
            '--path' => 'database/migrations/chatbot',
            '--realpath' => true,
        ]);
    }
}
