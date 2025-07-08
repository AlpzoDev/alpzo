<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Native\Laravel\Facades\ChildProcess;
use Native\Laravel\Facades\Notification;

class PHP
{

    public static function allVersions(): Collection
    {
        $port = 9000;
        return collect(Storage::disk('php')->directories())->map(function ($item) use (&$port) {
            if (Storage::disk('php')->exists($item . '\\php-cgi.exe')) {
                $processAlias = self::processAliasNameGenerate($item);
                return [
                    'name' => $item,
                    'process_alias' => $processAlias,
                    'path' => Storage::disk('php')->path($item),
                    'isIni' => Storage::disk('php')->exists($item . '\\php.ini'),
                    'php_cgi_path' => Storage::disk('php')->path($item . '\\php-cgi.exe'),
                    'port' => $port++,
                    'process' => ChildProcess::get($processAlias)
                ];
            }
            return null;

        });
    }

    public static function processAliasNameGenerate(string $version, string $service = 'php'): string
    {
        return $service . '-' . Str::replace(['-', '.'], '', $version);
    }

    public static function getVersions(): array
    {
        return self::allVersions()->toArray();
    }

    public static function start(array $version): void
    {
        if (!ChildProcess::get($version['process_alias'])) {
            $data = ChildProcess::start([

                    'php-cgi.exe',
                    '-b', '127.0.0.1:' . $version['port'],
                    '-c', $version['path'] . '\\php.ini',
                    '-d', 'cgi.force_redirect=0',
                    '-d', 'cgi.fix_pathinfo=1',

                    '-d', 'extension_dir=ext',
                ]
                , $version['process_alias'], $version['path']);
        }

    }

    public static function startAll(): void
    {
        self::allVersions()->each(function ($version) {
            if (!ChildProcess::get($version['process_alias'])) {
                self::start($version);
            }
        });
    }

    public static function stop(string $alias): void
    {
        ChildProcess::stop($alias);
    }

    public static function stopAll(): void
    {
        self::allVersions()->each(function ($version) {
            if (ChildProcess::get($version['process_alias'])) {
                self::stop($version['process_alias']);
            }
        });
    }

    public static function restart(string $alias): void
    {
        if (ChildProcess::get($alias)) {
            ChildProcess::restart($alias);
        }
    }

    public static function getPort(mixed $php_version)
    {
        $version = self::allVersions()->where('name', $php_version)->first();
        if ($version) {
            return $version['port'];
        }
        return 9000;
    }

    public static function getVersion(string $version): array
    {
        return self::allVersions()->where('name', $version)->first();
    }

    public static function isActive(string $version, string $service = 'php'): bool
    {
        return (bool)ChildProcess::get(self::processAliasNameGenerate($version, $service));
    }

    public static function restartAll(): void
    {
        self::allVersions()->each(function ($version) {
            self::restart($version['process_alias']);
        });
    }
}
