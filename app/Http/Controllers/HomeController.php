<?php

namespace App\Http\Controllers;

use App\Events\AlertEvent;
use App\Models\Project;
use App\Services\SettingService;
use FilesystemIterator;
use Inertia\Inertia;
use Native\Laravel\Facades\ChildProcess;

class HomeController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('index', [
            'services' => collect(ChildProcess::all())->map(function ($item, $key) {
                return [
                    'name' => $key,
                    'status' => true,
                ];
            })->except('cwd'),
            'allServices' => collect(\Storage::disk('bin')->directories())
                ->map(function ($item) {
                    return [
                        'name' => $item,
                        'status' => true,
                    ];
                })->filter(function ($item) {
                    if ($item['name'] == 'node' || $item['name'] == 'php') {
                        return null;
                    }
                    return $item;
                })->filter()
                ->toArray(),
            'projects' => Project::where('is_favorite', true)->orderBy('created_at', 'desc')->get()->toArray()
        ]);

    }

    public function about()
    {
        return Inertia::render('about');
    }

    public function help()
    {
        return Inertia::render('help', [
            'childProcess' => ChildProcess::all()
        ]);
    }


  public  function isHidden($file, $os)
    {
        if ($os === 'Windows') {
            // Windows için attrib komutu ile kontrol
            $attribute = shell_exec('attrib "' . $file . '"');
            return strpos($attribute, 'H') !== false;
        } else {
            // Unix tabanlı sistemlerde . ile başlayanlar
            return substr(basename($file), 0, 1) === '.';
        }
    }

    public function test()
    {
        $directory = 'C:\\alpzo\\www\\alpzo-front'; // Silinecek dizin
        $dryRun = false; // Gerçek silme işlemi yapmadan önce test et


        $os = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'Windows' : 'Unix';
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile() && $this->isHidden($fileInfo->getPathname(), $os)) {
                echo "Siliniyor: " . $fileInfo->getPathname() . PHP_EOL;
                if (!$dryRun) {
                    unlink($fileInfo->getPathname());
                }
            }
        }
    }
}
