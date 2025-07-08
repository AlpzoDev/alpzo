<?php

namespace App\Http\Controllers;

use App\Http\Requests\NodeVersionRequest;
use App\Services\NodeManagerService;
use Inertia\Inertia;

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
        NodeManagerService::install($request->validated());
    }
}
