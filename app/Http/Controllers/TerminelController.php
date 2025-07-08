<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;
use Native\Laravel\Facades\Window;

class TerminelController extends Controller
{
public function index()
{
return Inertia::render('terminel/index', [
'projects' => Project::all(),
]);
}

public function new()
{
Window::open('Alpzo Terminel')->minWidth(880)->minHeight(600)->width(900)->height(700)
->route('terminel.index')
->title('Alpzo Terminel');
}
}
