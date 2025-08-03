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
            'projects' => Project::where('is_favorite', true)->orderBy('created_at', 'desc')->get()->toArray(),
        ]);
    }
}
