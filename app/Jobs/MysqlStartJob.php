<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Facades\ChildProcess;

class MysqlStartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        try {
            ChildProcess::start([Storage::disk('bin')->path('mysql\\mysql-9.2.0-winx64\\bin\\mysqld.exe'), '--console'], 'mysql');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
