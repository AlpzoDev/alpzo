<?php

namespace App\Http\Middleware;

use App\Services\SettingService;
use Closure;
use Illuminate\Http\Request;

class isInstalledMiddleware
{
public function handle(Request $request, Closure $next)
{
return SettingService::getIsInstalled() ? $next($request) : redirect()->route('install.index');
}
}
