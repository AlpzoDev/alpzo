<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Facades\ChildProcess;
use Native\Laravel\Notification;

class NodeManagerService
{
    public static function nodeVersion(): Setting
    {
        if (!Setting::where('name', 'node_version')->exists()) {
            return Setting::create(['name' => 'node_version','data' => 'choose']);
        }
        return Setting::where('name', 'node_version')->first();
    }

    public static function nodeVersionList(): array
    {
        $path = 'alpzo/node/node-all-version.json';
        if (!Storage::exists($path) || Storage::lastModified($path) < time() - 60 * 60 * 24) {
            Artisan::call('alpzo:all-node-version');
        }
        return Storage::json($path);
    }

    public static function changeNodeVersion(string $nodeVersion): void
    {
        if (Storage::disk('node')->exists($nodeVersion)) {
            Setting::where('name', 'node_version')->update(['data' => $nodeVersion]);
            Notification::new()->title('Node Version Change')
                ->message('The node version  change was successful.')->show();
        } else {
            Notification::new()->title('Change Failed')
                ->message('The node version  change failed.')->show();
        }
    }

    public static function deleteNodeVersion(string $version): void
    {
        if (Storage::disk('node')->exists($version)) {
            File::deleteDirectory(Storage::disk('node')->path($version));
            Notification::new()->title('Delete Success')
                ->message('The node version  delete was successful.')->show();
        } else {
            Notification::new()->title('Delete Failed')
                ->message('The node version  delete failed.')->show();
        }
    }

    public static function showNodeFolder(string $version = ''): void
    {
        if (Storage::disk('node')->exists($version)) {
            $path = Storage::disk('node')->path($version);
            Process::run('explorer ' . $path);
        } else {
            Notification::new()->title('Show Failed')
                ->message('The node version  show failed.')->show();
        }
    }

    public static function getVersions(): array
    {
        return collect(Storage::disk('node')->directories())->map(function ($item) {
            return [
                'name' => $item,
                'value' => $item
            ];
        })->toArray();
    }

    public static function install(array $node): void
    {
        ChildProcess::artisan([
            'install:node-version',
            $node['version'],
            $node['download_url']['windows']['x64']
        ], 'install-node-version-' . $node['version']);
    }

}
