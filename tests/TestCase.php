<?php

namespace Jhavenz\LaravelHelpers\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Jhavenz\LaravelHelpers\LaravelHelpersServiceProvider;
use Jhavenz\LaravelHelpers\Tests\Fixtures\Post;
use Jhavenz\LaravelHelpers\Tests\Fixtures\User;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function afterRefreshingDatabase()
    {
        foreach (
            [
                User::class,
                Post::class,
            ] as $model
        ) {
            $model::migrate();
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelHelpersServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        //$app->loadEnvironmentFrom('../.env');
    }
}
