<?php

namespace App\Listeners;

use App\Events\ApacheStatusChangeEvent;
use Illuminate\Support\Facades\Process;
use Native\Laravel\Facades\ChildProcess;

class ApacheStatusChangeListener
{
public function __construct()
{
}

public function handle(ApacheStatusChangeEvent $event): void
{


}
}
