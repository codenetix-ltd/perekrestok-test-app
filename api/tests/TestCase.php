<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    /**
     * Setup the test environment.
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        DB::beginTransaction();
    }

    /**
     * Clean up the testing environment before the next test.
     * @return void
     */
    protected function tearDown()
    {
        DB::rollBack();
        parent::tearDown();
    }
}
