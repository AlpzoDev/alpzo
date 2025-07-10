<?php

namespace App\Providers;

use App\Events\AppServicesStopEvent;
use App\Jobs\AppFoldersCheckJob;
use App\Jobs\MysqlStartJob;
use App\Jobs\Nginx\StartJob;
use App\Jobs\PHP\PhpVersionsStartJob;
use App\Services\SettingService;
use Native\Laravel\Contracts\ProvidesPhpIni;
use Native\Laravel\Facades\GlobalShortcut;
use Native\Laravel\Facades\Menu;
use Native\Laravel\Facades\MenuBar;
use Native\Laravel\Facades\Window;


class NativeAppServiceProvider implements ProvidesPhpIni
{


    public function boot(): void
    {
        GlobalShortcut::key('alt+f4')
            ->event(AppServicesStopEvent::class)->register();
        Menu::make()->register();
        dispatch_sync(new AppFoldersCheckJob());
        $route = SettingService::getIsInstalled() == 1 ? 'dashboard' : 'install.index';
        Window::open()->minWidth(880)->minHeight(600)->width(900)->height(700)
            ->route($route)->title('Alpzo')
            ->afterOpen(function () {
                if (SettingService::getIsInstalled()) {
                    dispatch_sync(new StartJob());
                    dispatch_sync(new MysqlStartJob());
                    dispatch_sync(new PhpVersionsStartJob());
                }
            });
    }


    public function phpIni(): array
    {
        return [
            'memory_limit' => '4096M',
            'upload_max_filesize' => '2048M',
            'post_max_size' => '8000M',
            'max_execution_time' => '20000',
        ];
    }
}
