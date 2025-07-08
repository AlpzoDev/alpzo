<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
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
        Notification::new()->title('Node ' . $node['version'] . ' Download Starting...')
            ->message('The download is in progress.')->show();
        $response = Http::get($node['download_url']['windows']['x64']);
        if ($response->ok()) {
            Storage::disk('node')->put($node['version'] . '.zip', $response->body());
            Notification::new()->title('Download Success')
                ->message('The download was successful.')->show();

            $zip = new \ZipArchive();
            $zip->open(Storage::disk('node')->path($node['version'] . ".zip"));
            $zip->extractTo(Storage::disk('node')->path(''));
            $zip->close();
            Storage::disk('node')->delete($node['version'] . ".zip");
            Notification::new()->title('Unzip Success')
                ->message('The unzip was successful.')->show();
        } else {
            Notification::new()->title('Download Failed')
                ->message('The download failed.')->show();
        }
    }

}
