<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserMigrateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_users_table_has_the_expected_column(): void
    {
        $this->assertTrue(
            Schema::hasColumns('users', [
                'prefixname',
                'firstname',
                'middlename',
                'lastname',
                'suffixname',
                'username',
                'email',
                'password',
                'photo',
                'email_verified_at',
                'deleted_at'
            ]),
            1
        );
    }
}
