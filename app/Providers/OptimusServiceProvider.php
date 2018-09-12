<?php

namespace App\Providers;

use Jenssegers\Optimus\Optimus;
use Illuminate\Support\ServiceProvider;

class OptimusServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Optimus::class, function ($app) {
            return new Optimus(1580030173, 59260789, 1163945558);
        });
    }
}
