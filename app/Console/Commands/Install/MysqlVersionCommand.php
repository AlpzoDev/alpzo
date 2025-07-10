<?php

namespace App\Console\Commands\Install;

use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Facades\ChildProcess;
use Native\Laravel\Facades\Notification;
use ZipArchive;

class MysqlVersionCommand extends Command
{
    protected $signature = 'install:mysql-version';

    protected $description = 'Install mysql version 9.2.0';

    public function handle(): void
    {
        try {
            $response = Http::timeout(86400)->get('https://dev.mysql.com/get/Downloads/MySQL-8.4/mysql-8.4.5-winx64.zip');
            if ($response->ok()) {
                $mysqlPath = Storage::disk('bin')->path('mysql\\mysql-8.4.5-winx64.zip');
                File::put($mysqlPath, $response->body());
                if (!File::exists($mysqlPath)) {
                    Notification::new()->title('Error')->message('mysql-8.4.5-winx64.zip not found.')->show();
                } else {
                    Notification::new()->title('Success')->message('mysql-8.4.5-winx64.zip found.')->show();
                    $zip = new ZipArchive();
                    $zip->open($mysqlPath);
                    $zip->extractTo(Storage::disk('bin')->path('mysql'));
                    $zip->close();

                    ChildProcess::start([
                        Storage::disk('bin')->path('mysql\\mysql-8.4.5-winx64') . '\\bin\\mysqld.exe', '--initialize-insecure', '--console'],
                        'mysql-setup');

                    Storage::disk('bin')->delete('mysql\\mysql-8.4.5-winx64.zip');
                    Notification::new()->title('Success')->message('mysql-8.4.5-winx64.zip installed.')->show();
                }
            }

        } catch (ConnectionException $e) {
            Notification::new()->title('Error')->message('ConnectionException ' . $e->getMessage())->show();
        }

    }
}
