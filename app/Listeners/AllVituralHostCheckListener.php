<?php

namespace App\Listeners;

use App\Events\AllProjectCheckEvent;
use App\Jobs\VituralHostGenerateJob;
use App\Models\Project;
use App\Services\SettingService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AllVituralHostCheckListener
{
public function __construct()
{
}

public function handle(AllProjectCheckEvent $event): void
{
if (SettingService::getAutoVirtualHost() == '1') {
$projects = Project::all();
foreach ($projects as $project) {
$configPath = Storage::disk('etc')->path('apache/vhosts/auto.' .$project->url . '.conf');
if (!File::exists($configPath)) {
    dispatch_sync(new VituralHostGenerateJob($project));
}
}
}
}
}
