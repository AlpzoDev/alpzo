<?php

namespace App\Http\Controllers\Menubar;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\PHPManagerService;
use Inertia\Inertia;
use Native\Laravel\Facades\ChildProcess;

class IndexController extends Controller
{
    public function index()
    {
            $phpManagerService = new PHPManagerService();

            return Inertia::render('menubar/index', [
                'projects' => Project::where('is_favorite', true)->orderBy('created_at', 'desc')->get()->toArray(),
                'allPhpVersions' => $phpManagerService->getPhpVersionDownloadList(),
            ]);
    }

    public function phpVersions()
    {
        try {
            $phpManagerService = new PHPManagerService();

            return Inertia::render('menubar/php-versions', [
                'phpVersions' => $phpManagerService->getPhpVersionDownloadList(),
                'installedVersions' => $phpManagerService->getInstalledVersions(),
                'globalPhpVersion' => $phpManagerService->getGlobalPhpVersion(),
            ]);
        } catch (\Exception $e) {
            return Inertia::render('menubar/php-versions', [
                'phpVersions' => [],
                'installedVersions' => [],
                'globalPhpVersion' => null,
            ]);
        }
    }
}
