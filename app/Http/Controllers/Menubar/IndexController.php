<?php

namespace App\Http\Controllers\Menubar;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Inertia\Inertia;
use Native\Laravel\Facades\ChildProcess;

class IndexController extends Controller
{
    public function index()
    {
        return Inertia::render('menubar/index', [
            'version' => '0.9.0',
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
}
