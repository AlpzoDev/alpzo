<?php

namespace App\Services\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Facades\Notification;

trait BackupIniTrait
{
public static function backupPhpIniFolderPath(string $phpVersion): string
{
return Storage::disk('php')->path($phpVersion.'/php.ini-backup');
}
public static function backupIniCheck(string $phpVersion): bool
{

return File::exists(self::backupPhpIniFolderPath($phpVersion));
}

public static function isBackupIniFolderEmpty( string $phpVersion): bool
{
return File::isEmptyDirectory(self::backupPhpIniFolderPath($phpVersion));
}

public static function deleteBackupPhpIniFile(string $phpVersion, string $file): void
{
$path = self::backupPhpIniFolderPath($phpVersion).'/'.$file;
if (File::exists($path)) {
File::delete($path);
Notification::new()->title('Delete Success')->message('The delete was successful.')->show();
}
else{
Notification::new()->title('Delete Failed')->message('The delete failed.')->show();
}
}

public static function createBackupPhpIniFile(string $phpVersion, string $content): void
{
$path = self::backupPhpIniFolderPath($phpVersion);
if (!File::exists($path)) {
File::makeDirectory($path);
}
File::put($path.'/'.time().'.ini', $content);
}

public static function getBackupPhpIniFileList(string $phpVersion): array
{
return File::files(self::backupPhpIniFolderPath($phpVersion));
}


}
