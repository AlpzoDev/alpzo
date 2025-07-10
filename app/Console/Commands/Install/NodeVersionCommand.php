<?php

namespace App\Console\Commands\Install;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Native\Laravel\Notification;

class NodeVersionCommand extends Command
{
    protected $signature = 'install:node-version {version} {url}';

    protected $description = 'Install Node Version';

    public function handle(): void
    {
        $url = $this->argument('url');
        $version = $this->argument('version');
        $response = Http::get($url);
        if ($response->ok()) {
            Storage::disk('node')->put($version . '.zip', $response->body());
            Notification::new()->title('Download Success')
                ->message('The download was successful.')->show();

            $zip = new \ZipArchive();
            $zip->open(Storage::disk('node')->path($version . ".zip"));
            $zip->extractTo(Storage::disk('node')->path(''));
            $zip->close();
            Storage::disk('node')->delete($version . ".zip");
            Notification::new()->title('Unzip Success')
                ->message('The unzip was successful.')->show();
        } else {
            Notification::new()->title('Download Failed')
                ->message('The download failed.')->show();
        }
    }
}
