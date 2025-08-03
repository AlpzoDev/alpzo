<?php

namespace App\Console\Commands\Install;

use App\Services\PHP;
use App\Services\PHPManagerService;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Facades\ChildProcess;
use Native\Laravel\Notification;

class PhpVersionCommand extends Command
{
    protected $signature = 'install:php-version {name}';

    protected $description =  'Install PHP Version';

    public function handle()
    {
        $php = [
            'name' => $this->argument('name'),
        ];
        try {
            $response = Http::timeout(20000)->get("https://windows.php.net/downloads/releases/archives/php-" . $php['name'] . ".zip");
            if ($response->ok()) {
                Notification::new()->title('Downloading ...')
                    ->message('The download is in progress.')->show();

                Storage::disk('php')->put($php['name'] . '.zip', $response->body());
                Notification::new()->title('Download Success')
                    ->message('The download was successful.')->show();
                $zip = new \ZipArchive();
                $zip->open(Storage::disk('php')->path($php['name'] . '.zip'));
                $zip->extractTo(Storage::disk('php')->path($php['name'] . '/'));
                $zip->close();
                Storage::disk('php')->delete($php['name'] . '.zip');
                Notification::new()->title('Unzip Success')
                    ->message('The unzip was successful.')->show();
                defer(function () use ($php) {
                    ChildProcess::artisan('nginx:conf-re-generate', 'nginx-conf-regenerate');
                    PHPManagerService::getPhpIniDevelopmentFileCopy($php['name']);
                    PHP::stopAll();
                    PHP::startAll();
                    PHP::restart('nginx');

                });

            }

        } catch (ConnectionException $e) {
            Notification::new()->title('Download PHP version Failed')
                ->message('The php '. $this->argument('name').' download failed.')->show();
        }
    }

}
