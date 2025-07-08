<?php

namespace App\Providers;


use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Native\Laravel\Events;
use Native\Laravel\Facades\ChildProcess;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
    }


    public function boot(): void
    {
        Event::listen(Events\Windows\WindowClosed::class, function ($event) {
            collect(ChildProcess::all())->each(function ($process): void {
                $process->stop();
            });
        });
    }
}
