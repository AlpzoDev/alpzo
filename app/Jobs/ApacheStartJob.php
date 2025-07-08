<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Facades\ChildProcess;

class ApacheStartJob implements ShouldQueue
{
use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

public function __construct()
{
}

public function handle(): void
{
ChildProcess::start(
Storage::disk('apache')->path('Apache24\\bin').'\\httpd.exe',
'apache'
);
}
}
