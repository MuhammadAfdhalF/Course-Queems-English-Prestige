<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $activeDb = DB::connection()->getDatabaseName();

        if ($activeDb === 'queens_english_db' || ! str_ends_with($activeDb, '_test')) {
            throw new RuntimeException(
                sprintf(
                    'CRITICAL SAFETY GUARD: Attempted to run tests against unsafe database "%s". Tests must use a dedicated *_test database (e.g. queens_english_test).',
                    $activeDb
                )
            );
        }
    }
}
