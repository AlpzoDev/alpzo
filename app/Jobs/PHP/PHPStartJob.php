<?php

namespace App\Jobs\PHP;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Facades\ChildProcess;
use Native\Laravel\Facades\Notification;

class PHPStartJob implements ShouldQueue
{
use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

public function __construct(public $version, public int $port,public string $alias)
{
}

public function handle(): void
{
try {
$command = 'php-cgi -b 127.0.0.1:' . $this->port
;
ChildProcess::start($command, $this->alias, Storage::disk('php')->path($this->version));
} catch (\Exception $e) {
Notification::new()->title('Start Failed')->message($e->getMessage())->show();
}
}
}
