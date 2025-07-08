<?php

namespace App\Jobs\Nginx;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Facades\ChildProcess;

class StartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        if (!ChildProcess::get('nginx')) {
            ChildProcess::start('nginx.exe', 'nginx', Storage::disk('bin')->path('nginx\\nginx-1.24.0'));
        }
    }
}
