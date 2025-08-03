<?php

namespace App\Console\Commands\Install;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Notification;
use ZipArchive;

class NodeVersionCommand extends Command
{
    protected $signature = 'install:node-version {version} {url}';

    protected $description = 'Install Node Version';

    public function handle(): void
    {
        $url = $this->argument('url');
        $version = $this->argument('version');
        try {
            $response = Http::timeout(20000)->get($url);
            if ($response->ok()){
                Notification::new()->title('Download Start')
                    ->message('The node ' . $version . '  download started.')->show();
                Storage::disk('node')->put($version . '.zip', $response->body());
                Notification::new()->title('Download Success')
                    ->message('The node ' . $version . ' download was successful.')->show();
                $zip = new \ZipArchive();
                $zip->open(Storage::disk('node')->path($version . ".zip"));
                $zip->extractTo(Storage::disk('node')->path(''));
                $zip->close();
                Storage::disk('node')->delete($version . ".zip");
                Notification::new()->title('Unzip Success')
                    ->message('The unzip was successful.')->show();
            }

        } catch (\Exception $exception) {
            Notification::new()->title('Install Failed')
            ->message('The node '  . $version . '  install failed.')->show();
        }
    }
}
