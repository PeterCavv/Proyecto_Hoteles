<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Inertia\Inertia;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (app()->environment('testing')) {
            Inertia::setRootView('tests::blank');
        }
    }
}
