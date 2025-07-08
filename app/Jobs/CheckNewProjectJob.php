<?php

namespace App\Jobs;

use App\Enums\ProjectType;
use App\Models\Path;
use App\Models\Project;
use App\Services\NodeManagerService;
use App\Services\PHPManagerService;
use App\Services\ProjectService;
use App\Services\SettingService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Native\Laravel\Facades\Notification;

class CheckNewProjectJob
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {

        $projects = Project::all();
        $host = SettingService::getHostName();
        $paths = Path::all();
        $globalPhpVersion = PHPManagerService::modelData()->data;
        $nodeVersion = NodeManagerService::nodeVersion()->data;
        $service = new ProjectService();
        foreach ($paths as $path) {
            if (File::exists($path->path)) {
                $disk = File::directories($path->path);
                foreach ($disk as $dir) {
                    if (!$projects->where('path', $dir)->first()) {
                        $pr = Project::create([
                            'path' => $dir,
                            'name' => File::basename($dir),
                            'type' => ProjectType::BLANK,
                            'php_version' => $globalPhpVersion,
                            'node_version' => $nodeVersion,
                            'is_secure' => false,
                            'is_favorite' => false,
                            'server' => 'nginx',
                            'url' => Str::replace(' ', '-', File::basename($dir)) . '.' . $host
                        ]);
                        $service->setProject($pr)->projectCreate();
                        unset($pr);
                    }

                }
            } else {
                Notification::new()->title('Path Error')->message('The path does not exist.')->show();
            }
        }


    }
}
