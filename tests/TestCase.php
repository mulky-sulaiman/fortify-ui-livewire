<?php

namespace MulkySulaiman\FortifyUILivewire\Tests;

use MulkySulaiman\FortifyUILivewire\FortifyUILivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            FortifyUILivewireServiceProvider::class,
        ];
    }
}
