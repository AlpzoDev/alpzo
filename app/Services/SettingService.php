<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Native\Laravel\Facades\ChildProcess;

class SettingService
{
    public static function get(string $key, string $default): string
    {
        if (!Setting::where('name', $key)->exists()) {
            return Setting::create(['name' => $key, 'data' => $default])->data;
        }
        return Setting::where('name', $key)->first()->data;
    }

    public static function set(string $key, string $value): string
    {
        if (!Setting::where('name', $key)->exists()) {
            return Setting::create(['name' => $key, 'data' => $value])->data;
        }
        Setting::where('name', $key)->update(['data' => $value]);
        return $value;
    }


    public static function getHostName(): string
    {
        return self::get('host_name', 'test');
    }

    public static function setHostName(string $value): string
    {
        return self::set('host_name', $value);
    }

    public static function getAutoVirtualHost(): string
    {
        return self::get('auto_virtual_host', '1');
    }

    public static function setAutoVirtualHost(bool $value): string
    {
        return self::set('auto_virtual_host', $value);
    }


    public static function getDefaultPath(): string
    {
        return self::get('default_path', 'C:\\alpzo\\www');
    }

    public static function setDefaultPath(string $value): string
    {
        return self::set('default_path', $value);
    }

    public static function getServices(): array
    {
        $processes = collect(ChildProcess::all());
        return collect(Storage::disk('bin')->directories())
            ->filter(function ($item) {
                if ($item == 'php' || $item == 'node') {
                    return null;
                }
                return $item;
            })
            ->map(function ($item) use ($processes) {

                return [
                    'name' => Str::upper($item),
                    'status' => $processes->has($item)
                ];
            })
            ->filter()
            ->toArray();
    }

    public static function setIsInstalled($value): bool
    {
        return (bool)self::set('is_installed', (string)$value);
    }

    public static function getIsInstalled(): bool
    {
        return self::get('is_installed', '0');
    }


}
