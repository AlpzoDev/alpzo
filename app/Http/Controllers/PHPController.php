<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewPHPVersionRequest;
use App\Models\Project;
use App\Services\PHP;
use App\Services\PHPManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Native\Laravel\Facades\Notification;

class PHPController extends Controller
{
    public function index()
    {
        return Inertia::render('php/index',
            [
                'projectPhpVersion' => Project::get(['php_version'])->pluck('php_version')->toArray(),

            ]);
    }

    public function create()
    {
        return Inertia::render('php/create', [
            'allPHPVersions' => PHPManagerService::getPhpVersionDownloadList()
        ]);
    }

    public function showPHPFolder(string $phpVersion = '')
    {
        if (!Storage::disk('php')->exists($phpVersion)) {

            Notification::new()->title('PHP Version Not Found')
                ->message('The php version does not exist.')->show();
            return redirect()->route('php.index');
        }
        PHPManagerService::showPHPFolder($phpVersion);
    }

    public function show(string $phpVersion)
    {
        if (!Storage::disk('php')->exists($phpVersion)) {
            Notification::new()->title('PHP Version Not Found')
                ->message('The php version does not exist.')->show();
            return redirect()->route('php.index');
        }
        return Inertia::render('php/show', [
            'phpVersion' => $phpVersion,
            'activePHPExtensions' => PHPManagerService::getActivePHPExtensions($phpVersion),
            'phpIni' => PHPManagerService::getPhpIniData($phpVersion),
            'isIniBackup' => File::exists(Storage::disk('php')->path($phpVersion . '/php.ini-backup')),

            'projects' => Project::where('php_version', $phpVersion)->get()->toArray(),
            'isActive' => PHP::isActive($phpVersion)

        ]);
    }


    public function installPHP(NewPHPVersionRequest $request)
    {
      return  PHPManagerService::download($request->validated());
    }

    public function changePHPVersion(string $phpVersion): void
    {
        PHPManagerService::changePHPVersion($phpVersion);
    }

    public function removePHP(string $phpVersion): void
    {
        PHPManagerService::deletePhpVersion($phpVersion);
    }

    public function phpIniReset(string $phpVersion)
    {
        PHPManagerService::getPhpIniDevelopmentFileCopy($phpVersion, true);
        PHP::restart(PHP::processAliasNameGenerate($phpVersion));
        return response()->json(['status' => 'success',
            'phpIni' => PHPManagerService::getPhpIniData($phpVersion)]);
    }

    public function updatePhpIni(string $phpVersion, Request $request)
    {
        File::put(Storage::disk('php')->path($phpVersion . '/php.ini-backup'),
            File::get(Storage::disk('php')->path($phpVersion . '/php.ini')));
        File::put(Storage::disk('php')->path($phpVersion . '/php.ini'), $request->phpIni);
        return response()->json(['status' => 'success',
            'phpIni' => PHPManagerService::getPhpIniData($phpVersion)]);
    }

    public function backupIni(string $phpVersion)
    {
        if (File::exists(Storage::disk('php')->path($phpVersion . '/php.ini-backup'))) {
            return response()->json([
                'status' => 'success',
                'phpIni' => File::get(Storage::disk('php')->path($phpVersion . '/php.ini-backup'))
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'phpIni' => File::get(Storage::disk('php')->path($phpVersion . '/php.ini'))
            ], 404);
        }

    }

    public function phpRestart(string $phpVersion)
    {
        PHP::restart(PHP::processAliasNameGenerate($phpVersion));
        Notification::new()->title('PHP Restart')
            ->message('The php version ' . $phpVersion . ' was restarted successfully.')->show();
    }

    public function phpStart(string $phpVersion): void
    {
        PHP::start(PHP::allVersions()->where('name', $phpVersion)->first());
        Notification::new()->title('PHP Start')
            ->message('The php version ' . $phpVersion . ' was started successfully.')->show();
    }

    public function phpStop(string $phpVersion): void
    {
        PHP::stop(PHP::processAliasNameGenerate($phpVersion));
        Notification::new()->title('PHP Stop')
            ->message('The php version ' . $phpVersion . ' was stop successfully.')->show();
    }

    public function allStart()
    {
        PHP::startAll();
        Notification::new()->title('PHP Start')
            ->message('The all php versions were started successfully.')->show();
        return back();
    }

    public function allStop()
    {
        PHP::stopAll();
        Notification::new()->title('PHP Stop')
            ->message('The all php versions were stop successfully.')->show();
        return back();
    }

    public function allRestart()
    {
        PHP::restartAll();
        Notification::new()->title('PHP Restart')
            ->message('The all php versions were restarted successfully.')->show();
        return back();
    }
}
