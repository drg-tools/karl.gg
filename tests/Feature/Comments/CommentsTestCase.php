<?php

namespace Tests\Feature\Comments;

use App\Comments\Formatter;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class CommentsTestCase extends TestCase
{
    use RefreshDatabase;

    protected User $testUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testUser = User::factory()->create();
        $this->app->instance(Formatter::class, new FormatterFake($this->app['cache.store']));
    }
}
