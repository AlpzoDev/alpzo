<?php

namespace App\Services\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
trait PHPIniManagmentTrait
{


public static function getPhpIniCheck(string $phpVersion): bool
{
return File::exists(Storage::disk('php')->path($phpVersion.'/php.ini'));
}

public static function getPhpIniDevelopmentFileCopy(string $phpVersion, bool $force = false): bool
{
if (self::getPhpIniCheck($phpVersion) && $force) {
return File::put(Storage::disk('php')->path($phpVersion.'/php.ini'), File::get(Storage::disk('php')->path($phpVersion.'/php.ini-development')));
}
return File::copy(Storage::disk('php')->path($phpVersion.'/php.ini-development'), Storage::disk('php')->path($phpVersion.'/php.ini'));
}

public static function getPhpIniData(string $phpVersion): string
{
if (!self::getPhpIniCheck($phpVersion)) {
self::getPhpIniDevelopmentFileCopy($phpVersion);
}
return File::get(Storage::disk('php')->path($phpVersion.'/php.ini'));
}

}
