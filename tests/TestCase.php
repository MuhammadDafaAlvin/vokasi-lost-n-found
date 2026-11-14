<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Carbon;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use TestAuth;
    use TestHttp;

    /**
     * Setup the test environment
     */
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-08-29T00:08:14.329300Z');
    }
}
