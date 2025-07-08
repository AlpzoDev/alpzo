<?php

namespace App\Console\Commands;

use App\Jobs\Nginx\ProjectConfJob;
use App\Models\Project;
use App\Services\PHP;
use Illuminate\Console\Command;
use Native\Laravel\Facades\Notification;

class NginxConfReGenerateCommand extends Command
{
protected $signature = 'nginx:conf-re-generate';

protected $description = 'Command description';

public function handle(): void
{
$projects = Project::all();

foreach ($projects as $project) {
dispatch_sync(new ProjectConfJob($project));
}
PHP::restart('nginx');
Notification::new()
->title('Nginx Conf Regenerate Success')
->message('All project  nginx conf was regenerated successfully.')
->show();
}
}
