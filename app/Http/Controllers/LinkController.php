<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class LinkController extends Controller
{
public function github(string $repo): void
{
Process::run('start https://github.com/' . Str::replace('.', "/", $repo));
}

public function url(Request $request): \Illuminate\Http\RedirectResponse
{
Process::run('start '.$request->url);
return redirect()->back();
}
}
