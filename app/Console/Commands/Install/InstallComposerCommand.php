<?php
declare(strict_types=1);
namespace App\Console\Commands\Install;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Native\Laravel\Facades\ChildProcess;

class InstallComposerCommand extends Command
{
    protected $signature = 'alpzo:install-composer';

    protected $description = 'Command description';

    public function handle(): void
    {
        if (Process::run('composer --version')->successful()) {
            $this->info('Composer already installed');
            return;
        }
        $this->info('Composer install start');
        $response = Http::get('https://getcomposer.org/installer');
        if ($response->successful()) {
            if (!File::exists('C:\\alpzo\\apps')) {
                File::makeDirectory('C:\\alpzo\\apps');
                File::makeDirectory('C:\\alpzo\\apps\\composer');
                File::put('C:\\alpzo\\apps\\composer\\composer.phar', $response->body());
                ChildProcess::php('C:\\alpzo\\apps\\composer\\composer.phar', 'install');
                $this->info('Composer install finish');
            }
        }
    }
}
