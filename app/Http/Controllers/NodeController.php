<?php

namespace App\Http\Controllers;

use App\Http\Requests\NodeVersionRequest;
use App\Services\NodeManagerService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Native\Laravel\Notification;

class NodeController extends Controller
{
    public function index()
    {
        return Inertia::render('node/index');
    }

    public function create()
    {
        return Inertia::render('node/create', [
            'allNodeVersions' => NodeManagerService::nodeVersionList()
        ]);
    }

    public function changeNodeVersion(string $nodeVersion)
    {
        NodeManagerService::changeNodeVersion($nodeVersion);
    }

    public function deleteNodeVersion(string $nodeVersion)
    {
        NodeManagerService::deleteNodeVersion($nodeVersion);
    }

    public function showNodeFolder(string $nodeVersion = '')
    {
        NodeManagerService::showNodeFolder($nodeVersion);
    }


    public function installNode(NodeVersionRequest $request)
    {
        if(File::exists(Storage::disk('node')->path($request->validated()['version']))) {
            Notification::new()->title('Install Failed')
                ->message('The node version  already exists.')->show();
            return[
                'install' => false
            ];
        }
        NodeManagerService::install($request->validated());
        return[
            'install' => true
        ];
    }
}
