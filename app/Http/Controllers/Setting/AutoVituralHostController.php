<?php

namespace App\Http\Controllers\Setting;

use App\Events\AllProjectCheckEvent;
use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Native\Laravel\Facades\Notification;


class AutoVituralHostController extends Controller
{
public function __invoke()
{
$bool= SettingService::getAutoVirtualHost();
$bool = $bool === '1' ? '0' : "1";
SettingService::setAutoVirtualHost( $bool);

Notification::new()->title('Success')->message($bool?'The automatic project scan was successfully.':'The automatic project scan was disabled.')->show();
}
}
