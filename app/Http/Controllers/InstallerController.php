<?php

namespace App\Http\Controllers;

use App\Models\Path;
use App\Services\NodeManagerService;
use App\Services\PHPManagerService;
use App\Services\SettingService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Native\Laravel\Facades\ChildProcess;
use Native\Laravel\Facades\Notification;
use ZipArchive;

class InstallerController extends Controller
{
    public function index()
    {
        $git = Process::run('git --version')->output();
        $composer = Process::run('composer --version')->output();

        return Inertia::render('installer/index', [
            'git' => $git,
            'composer' => $composer,
            'allPhpVersions' => collect(PHPManagerService::getPhpVersionDownloadList())->take(4)->toArray(),
            'allNodeVersions' => collect(NodeManagerService::nodeVersionList())->take(5)->toArray()
        ]);
    }

    /**
     * @throws FileNotFoundException
     * @throws ConnectionException
     */
    public function post(Request $request)
    {
        if (Path::where('name', 'alpzo')->count() <= 0) {
            Path::create([
                'path' => Storage::disk("www")->path(''),
                'name' => 'alpzo',
                'is_default' => true,
                'description' => ''
            ]);
        }

        SettingService::setDefaultPath(Storage::disk("www")->path(''));

        if (!File::exists(Storage::disk('bin')->path('nginx'))) {
            File::makeDirectory(Storage::disk('bin')->path('nginx'), 0755, true);
        }
        if (!File::exists(Storage::disk('bin')->path('nginx\\nginx-1.24.0'))) {
            $response = Http::get('https://nginx.org/download/nginx-1.24.0.zip');
            if ($response->ok()) {
                $nginxPath = Storage::disk('bin')->path('nginx\\nginx-1.24.0.zip');
                File::put($nginxPath, $response->body());
                $zip = new ZipArchive();
                $zip->open($nginxPath);
                $zip->extractTo(Storage::disk('bin')->path('nginx'));
                $zip->close();
                $nginxConfPath = public_path('alpzo\\nginx.conf');
                File::delete($nginxPath);
                if (File::exists($nginxConfPath)) {
                    if (File::put(Storage::disk('bin')->path('nginx\\nginx-1.24.0\\conf\\nginx.conf'), File::get($nginxConfPath))) {
                        Notification::new()->title('Success')->message('nginx.conf created.')->show();
                    } else {
                        Notification::new()->title('Error')->message('nginx.conf not write.')->show();
                    }
                } else {
                    Notification::new()->title('Error')->message('nginx.conf not found.')->show();
                }

            }
        }

        if (!File::exists(Storage::disk('bin')->path('mysql'))) {
            File::makeDirectory(Storage::disk('bin')->path('mysql'), 0755, true);
        }

        if (!File::exists(Storage::disk('mysql')->path('mysql-9.2.0-winx64'))) {
            ChildProcess::artisan('install:mysql-version', 'install-mysql-version');
        }

        defer(function () use ($request) {
            if ($request->has('php') && $request->php != []) {
                foreach ($request->php as $php) {
                    PHPManagerService::download($php);
                }
                PHPManagerService::modelData()->update([
                    'data' => $request->php[0]['name']
                ]);
            }


            if ($request->has('node') && $request->node != []) {
                foreach ($request->node as $node) {
                    NodeManagerService::install($node);
                }
                NodeManagerService::nodeVersion()->update([
                    'data' => 'node-' . $request->node[0]['version'] . '-win-x64'
                ]);
            }


        }, 'install-node-php');
        SettingService::setIsInstalled('1');
        Notification::new()->title('Success')->message('Installed successfully.')->show();
        return redirect()->route('dashboard');
    }

}
