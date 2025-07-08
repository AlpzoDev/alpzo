<?php

namespace App\Http\Middleware;

use App\Services\SettingService;
use Closure;
use Illuminate\Http\Request;

class isNotInstalledMiddleware
{
public function handle(Request $request, Closure $next)
{
if (SettingService::getIsInstalled())
return redirect()->route('dashboard');
return $next($request);
}
}
