<?php

namespace App\Listeners;

use App\Events\AppServicesStopEvent;
use Native\Laravel\Facades\ChildProcess;

class AllChildProcessStopedListener
{
public function __construct()
{
}

public function handle(AppServicesStopEvent $event): void
{
collect(ChildProcess::all())->each(function ($process) {
$process->stop();
});
}
}
