<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Jobs\MysqlStartJob;
use App\Jobs\Nginx\StartJob;
use App\Jobs\PHP\PHPStartJob;
use App\Services\SettingService;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Facades\ChildProcess;
use Native\Laravel\Facades\Notification;

class AllServiceController extends Controller
{
    public function restart(string $service)
    {
        if (in_array($service, Storage::disk('bin')->directories()) && ChildProcess::get($service)) {
            ChildProcess::restart($service);
            Notification::new()->title('Restart Success')->message('The service has been restarted')->show();
        } else {
            Notification::new()->title('Restart Failed')->message('The service is not found')->show();
        }
    }

    public function stop(string $service)
    {
        if (in_array($service, Storage::disk('bin')->directories()) && ChildProcess::get($service)) {
            ChildProcess::stop($service);
            Notification::new()->title('Stop Success')->message('The service has been stopped')->show();
        } else {
            Notification::new()->title('Stop Failed')->message('The service is not found')->show();
        }
    }

    public function start(string $service)
    {
        if (in_array($service, Storage::disk('bin')->directories()) && !ChildProcess::get($service)) {
            if ($service == 'nginx') {
                dispatch_sync(new StartJob());
                Notification::new()->title('Start Success')->message('The service nginx has been started')->show();
            } elseif ($service == 'mysql') {
                dispatch_sync(new MysqlStartJob());
                Notification::new()->title('Start Success')->message('The service mysql has been started')->show();
            } elseif ($service == 'php') {
                $res = SettingService::get('global_php_version_path', 'choose');
                if ($res == 'choose') {
                    Notification::new()->title('Start Failed')->message('The service is not found')->show();
                    return;
                } else {
                    dispatch_sync(new PHPStartJob($res, 9000, $service));
                    Notification::new()->title('Start Success')->message('The service php has been started')->show();
                }
            } else {
                Notification::new()->title('Start Failed')->message('The service is not found')->show();
                return;
            }
        } else {
            Notification::new()->title('Start Failed')->message('The service is not found')->show();
        }
    }

    public function allStop()
    {
        $collect = collect(ChildProcess::all());
        if ($collect->isEmpty()) {
            Notification::new()->title('Stop Failed')->message('All service has been stopped')->show();
            return;
        }
        $collect->each(function ($process) {
            $process->stop();
        });
        Notification::new()->title('Stop Success')->message('All service has been stopped')->show();

    }
}
