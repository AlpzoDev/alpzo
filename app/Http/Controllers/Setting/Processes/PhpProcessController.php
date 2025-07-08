<?php

namespace App\Http\Controllers\Setting\Processes;

use App\Http\Controllers\Controller;
use App\Jobs\PHP\PhpVersionsStartJob;
use App\Jobs\PHP\UpStreamJob;
use App\Services\PHP;
use Inertia\Inertia;

class PhpProcessController extends Controller
{
public function index()
{
dispatch_sync(new UpStreamJob());
dispatch_sync(new PhpVersionsStartJob());
return Inertia::render('setting/process/php',[
'processes' => PHP::allVersions()->toArray()
]);
}
}
