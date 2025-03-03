<?php

namespace MulkySulaiman\FortifyUILivewire;

use Illuminate\Support\ServiceProvider;
use MulkySulaiman\FortifyUILivewire\Commands\FortifyUILivewireCommand;

class FortifyUILivewireServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/resources/views' => base_path('resources/views'),
                __DIR__.'/../stubs/app/Livewire' => base_path('app/Livewire'),
            ], 'fortify-ui-views');

            $this->publishes([
                __DIR__.'/../stubs/app/Providers' => base_path('app/Providers'),
            ], 'fortify-ui-provider');

            $this->commands([
                FortifyUILivewireCommand::class,
            ]);
        }
    }
}
