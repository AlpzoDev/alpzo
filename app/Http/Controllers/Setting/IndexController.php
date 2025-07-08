<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Path;
use App\Services\SettingService;
use Inertia\Inertia;

class IndexController extends Controller
{
public function __invoke()
{
return Inertia::render('setting/index', [
'paths'=> Path::all(),
'autoVirtualHost' => (bool)SettingService::getAutoVirtualHost(),
'defaultPath' => SettingService::getDefaultPath(),
'hostName' => SettingService::getHostName(),
'services' => SettingService::getServices()
]);
}
}
