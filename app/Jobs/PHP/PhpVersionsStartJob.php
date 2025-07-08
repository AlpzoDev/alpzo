<?php

namespace App\Jobs\PHP;

use App\Services\PHP;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Native\Laravel\Facades\ChildProcess;

class PhpVersionsStartJob implements ShouldQueue
{
use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

public function __construct()
{
}

public function handle(): void
{
PHP::startAll();
}
}
