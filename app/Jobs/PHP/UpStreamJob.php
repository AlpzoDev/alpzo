<?php

namespace App\Jobs\PHP;

use App\Services\PHP;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UpStreamJob implements ShouldQueue
{
use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

public function __construct()
{
}

public function handle(): void
{
$phpUpstreamPath = Storage::disk('etc')->path('nginx/php-upstream.conf');
$conf='';
PHP::allVersions()->each(function ($version) use (&$conf) {
$conf .="upstream {$version['process_alias']} {
    server 127.0.0.1:" . $version['port'] . ";
}
  ";
});
File::put($phpUpstreamPath, $conf);
}
}
