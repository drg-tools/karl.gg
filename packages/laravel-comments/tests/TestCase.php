<?php

namespace Hazzard\Comments\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use DatabaseTransactions;

    /**
     * @var \Hazzard\Comments\Tests\User
     */
    protected $testUser;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->createUsersTable();

        $this->artisan('migrate');

        $this->withFactories(__DIR__.'/factories');

        $this->testUser = factory(User::class)->create();

        $this->app->singleton('comments.formatter', function () {
            return new FormatterFake;
        });
    }

    /**
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('comments', array_merge(
            require __DIR__.'/../config/comments.php',
            [
                'throttle' => false,
                'auto_link' => false,
                'auto_email' => false,
                'auto_image' => false,
                'auto_video' => false,
                'media_embed' => false,
                'markdown' => false,
                'emoticons' => false,
                'bbcodes' => false,
                'detect_duplicates' => false,
                'max_unapproved' => false,
                'settings' => false,
            ]
        ));

        $app['config']->set('auth.providers.users.model', User::class);
    }

    /**
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Hazzard\Comments\CommentsServiceProvider::class,
        ];
    }

    /**
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    // protected function resolveApplicationExceptionHandler($app)
    // {
    //     $app->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, ExceptionHandler::class);
    // }

    /**
     * @return void
     */
    protected function createUsersTable()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('name');
        });
    }
}


class Formatter extends \Hazzard\Comments\Formatter
{
    protected function getRenderer()
    {
        return $this->getComponent('renderer');
    }
}

class AkismetFake implements \Hazzard\Comments\Contracts\Akismet
{
    public function commentCheck(array $params): bool
    {
        return false;
    }
}

class FormatterFake implements \Hazzard\Comments\Contracts\Formatter
{
    public function parse($text)
    {
        return $text;
    }
    public function unparse($xml)
    {
        return $xml;
    }
    public function render($xml)
    {
        return $xml;
    }
    public function flush()
    {
    }
}

// class ExceptionHandler extends \Orchestra\Testbench\Exceptions\Handler
// {
//     public function render($request, \Exception $e)
//     {
//         if ($request->expectsJson()) {
//             if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
//                 return response()->json($e->getMessage(), 403);
//             }

//             if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
//                 return response()->json($e->getMessage(), 404);
//             }
//         }

//         return parent::render($request, $e);
//     }
// }
