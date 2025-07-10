<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Facades\ChildProcess;
use Native\Laravel\Facades\Notification;

class MysqlStartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        try {
            if (!File::exists(Storage::disk('bin')->path('mysql\\mysql-8.4.5-winx64\\bin\\mysqld.exe'))) {
                Notification::new()->title('Error')->message('MySQL Error')->show();
            }
            else {
            ChildProcess::start([Storage::disk('bin')->path('mysql\\mysql-8.4.5-winx64\\bin\\mysqld.exe'), '--console'], 'mysql');

            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
