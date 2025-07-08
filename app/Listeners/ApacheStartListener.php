<?php

namespace App\Listeners;

use App\Events\AppStartEvent;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Facades\ChildProcess;

class ApacheStartListener
{
public function __construct()
{
}

public function handle(AppStartEvent $event): void
{
ChildProcess::start(Storage::disk('apache')->path('Apache24/bin/httpd.exe'),'apache');
}
}
