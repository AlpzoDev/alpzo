<?php

namespace App\Http\Controllers\Services;

use App\Events\ApacheStatusChangeEvent;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Native\Laravel\Facades\ChildProcess;
use function Pest\Laravel\get;

class ApacheController extends Controller
{
public function start(string $version)
{
if (!Setting::where('name', 'apache_version')->exists()) {
return Setting::create(['name' => 'apache_version', 'type' => 'string', 'data' => $version]);
}
return Setting::where('name', 'apache_version')->first()->data;
}

public function stop()
{
ChildProcess::stop('apache');
return response()->json(['status' => 'success']);
}

public function status()
{
event(new ApacheStatusChangeEvent());
return response()->json(['status' => 'success']);
}

public function restart()
{
ChildProcess::restart('apache');
return response()->json(['status' => 'success']);
}
}
