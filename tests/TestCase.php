<?php

namespace Jhavenz\LaravelHelpers\Tests;

use Jhavenz\LaravelHelpers\LaravelHelpersServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
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
