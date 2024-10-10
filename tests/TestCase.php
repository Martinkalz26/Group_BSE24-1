<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase; // If you want to use database migrations for tests
use Illuminate\Support\Facades\Auth; // For authentication features

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase; // Use this trait if you want to reset your database for each test

    /**
     * Prepare for the tests.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Additional setup can be done here
    }
}
