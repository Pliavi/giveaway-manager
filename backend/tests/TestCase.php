<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected User $loggedInUser;
    protected User $simpleUser;

    public function setup(): void
    {
        parent::setup();

        $users = User::factory(2)->create();
        $this->loggedInUser = $users[0];
        $this->simpleUser = $users[1];
    }
}
