<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Setting;
use App\Services\Traits\PHPIniManagmentTrait;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Native\Laravel\Facades\ChildProcess;
use Native\Laravel\Notification;

class PHPManagerService
{
    use PHPIniManagmentTrait;

    public static function download(array $php): bool | array
    {
        if (Storage::disk('php')->exists($php['name']) || Storage::disk('php')->exists('php-' . $php['name'] . '.zip')) {
            Notification::new()->title('Download Failed')
                ->message('The php version already exists.')->show();
            return [
                'install' => false
            ];
        }
       ChildProcess::artisan([
           'install:php-version',
           $php['name']
       ], 'install-php-version-' . $php['name']);

        return [
            'install' => true
        ];

    }

    public static function getInfo(string $version): string
    {
        return Process::path(Storage::disk('php')->path($version))->run('php.exe -i')->output();
    }

    public static function modelData(): Setting
    {
        if (!Setting::where('name', 'global_php_version_path')->exists()) {
            return Setting::create([
                'name' => 'global_php_version_path',
                'data' => 'choose'
            ]);
        }
        return Setting::where('name', 'global_php_version_path')->first();
    }

    public static function getVersions(): array
    {
        return collect(Storage::disk('php')->directories())->map(function ($item) {
            if (Storage::disk('php')->exists($item . '\\php.exe')) {
                return [
                    'name' => $item,
                    'value' => $item,
                    'isActive' => PHP::isActive($item)
                ];
            }
            return null;
        })->filter()
            ->values()
            ->toArray();
    }

    public static function getActivePHPExtensions(string $phpVersion)
    {
        return collect(explode("\n", trim(
            Process::path(Storage::disk('php')->path($phpVersion))
                ->run('php.exe -m')
                ->output()
        )))
            ->map(function ($line) {
                return Str::replace(['[Zend Modules]', '[PHP Modules]'], '', $line);
            })
            ->filter(fn($line) => !empty(trim($line)))
            ->values()
            ->toArray();
    }

    public static function changePhpVersion(string $phpVersion): bool
    {
        if (Setting::where('name', 'global_php_version_path')->update(['data' => $phpVersion])) {
            Notification::new()->title('PHP Version Changed')
                ->message('The PHP version has been changed to ' . $phpVersion . '.')->show();
            return true;
        } else {
            Notification::new()->title('PHP Version Changed')
                ->message('The PHP version has not been changed.')->show();
            return false;
        }
    }

    public static function getPhpVersionDownloadList(): array
    {
        $path = 'alpzo/php/php-all-version.json';

        if (!Storage::exists($path) || Storage::lastModified($path) < time() - 60 * 60 * 24) {
            Artisan::call('alpzo:php-all-version');
        }
        return Storage::json($path);

    }

    public static function deletePhpVersion($phpVersion): void
    {
        if (!Storage::disk('php')->exists($phpVersion) && self::modelData()->data !== $phpVersion) {
            Notification::new()->title('Delete Failed')
                ->message('The php version does not exist.')->show();
            return;
        }

        if (Project::where('php_version', $phpVersion)->exists()) {
            Notification::new()->title('Delete Failed')
                ->message('The php version is in use and cannot be deleted.')->show();
            return;
        }
        $phpPath = Storage::disk('php')->path($phpVersion);
        PHP::stop(PHP::processAliasNameGenerate($phpVersion));
        File::cleanDirectory($phpPath);
        File::deleteDirectory($phpPath);
        Notification::new()->title('Delete Success')
            ->message('The delete was successful.')->show();
        defer(function () {
            ChildProcess::artisan('nginx:conf-re-generate', 'nginx-conf-regenerate');
            PHP::stopAll();
            PHP::startAll();
            PHP::restart('nginx');
        });
    }

    public static function showPHPFolder(string $phpVersion = ''): void
    {
        if (!Storage::disk('php')->exists($phpVersion)) {
            Notification::new()->title('Open Failed')
                ->message('The php version does not exist.')->show();
            return;
        }
        Process::run('explorer ' . Storage::disk('php')->path($phpVersion));
    }

    public static function getPhpIniExtensions(string $phpVersion)
    {
        $content = self::getPhpIniData($phpVersion);
        return preg_match_all('/^(?:;\s*)?extension\s*=\s*(.*)/mi', $content, $matches) ? collect($matches[1])
            ->map(function ($extension) {
                return Str::replace(['.dll', '.so', '.dylib', 'php_', 'zend_', '.dll\\r'], '', $extension);
            })
            ->filter()->values()->toArray() : [];
    }

    public static function allPHPExtensions(string $phpVersion): array
    {
        $phpVersionPath = Storage::disk('php')->path($phpVersion . '/ext');
        return collect(File::files($phpVersionPath))->map(function ($item) use ($phpVersionPath) {
            return Str::replace(['.dll', '.so', '.dylib', 'php_', 'zend_', '.dll\\r', $phpVersionPath . '\\'], '', $item);
        })->toArray();
    }
}
