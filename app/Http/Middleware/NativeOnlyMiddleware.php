<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;


class NativeOnlyMiddleware
{
public function handle(Request $request, Closure $next)
{
$userAgent = $request->header('User-Agent');
if ($this->isBrowser($userAgent)) {
return Inertia::render('errors/notApp');
}
return $next($request);
}

private function isBrowser(string $userAgent): bool
{
if (Str::contains($userAgent, 'Alpzo') && Str::contains($userAgent, 'Electron')) {
return false; 
}
return true;
}
}
