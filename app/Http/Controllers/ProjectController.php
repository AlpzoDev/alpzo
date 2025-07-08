<?php

namespace App\Http\Controllers;

use App\Enums\ProjectType;
use App\Http\Requests\Project\ProjectRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use App\Jobs\CheckNewProjectJob;
use App\Jobs\Nginx\ProjectConfJob;
use App\Models\Path;
use App\Models\Project;
use App\Services\PHP;
use App\Services\PHPManagerService;
use App\Services\ProjectService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Native\Laravel\Notification;


class ProjectController extends Controller
{
    public function index()
    {
        if (SettingService::getAutoVirtualHost()) {
            dispatch_sync(new CheckNewProjectJob());
        }
        $projects = Project::orderBy('created_at', 'desc')->get();

        return Inertia::render('project/index', [
            'projects' => $projects,
            'hasProjects' => $projects->count() > 0,
            'projectTypes' => ProjectType::options(),
            'paths' => collect(Path::all())->map(function ($item) {
                return [
                    "name" => $item["name"] . '-' . $item["path"],
                    "value" => $item["path"]
                ];
            })->toArray(),
        ]);
    }

    public function create()
    {
        return Inertia::render('project/create', [
            'paths' => collect(Path::all())->map(function ($item) {
                return ["name" => $item["name"] . '-' . $item["path"],
                    "value" => $item["path"]];
            })->toArray(),
            'defaultPath' => SettingService::getDefaultPath(),
            'hostName' => SettingService::getHostName(),
            'projectTypes' => ProjectType::options()
        ]);
    }

    public function show(Project $project)
    {
        return Inertia::render('project/show', [
            'project' => $project,
            'isEmpty' => File::exists($project->path) && File::isEmptyDirectory($project->path),
            'hostName' => SettingService::getHostName(),
            'projectTypes' => ProjectType::options(),
            'isFolder' => File::exists($project->path),
            'composer' => File::exists($project->path . '\\composer.json') ? File::json($project->path . '\\composer.json') : null,
            'packages' => File::exists($project->path . '\\package.json') ? File::json($project->path . '\\package.json') : null,
            'technologies' => [
                'isGit' => File::exists($project->path . '\\.git'),
                'isNode' => File::exists($project->path . '\\node_modules') || File::exists($project->path . '\\package.json'),
                'isComposer' => File::exists($project->path . '\\vendor') || File::exists($project->path . '\\composer.json'),
                'isTailwind' => File::exists($project->path . '\\tailwind.config.js'),
                'isVite' => File::exists($project->path . '\\vite.config.js'),
                'isNuxt' => File::exists($project->path . '\\nuxt.config.js'),
                'isLaravel' => File::exists($project->path . '\\artisan'),
            ],
        ]);
    }

    public function showFolder(Project $project)
    {
        if (!File::exists($project->path)) {
            Notification::new()->title('Open Failed')
                ->message('The project folder does not exist.')->show();
        }else {
            Process::run('explorer ' . $project->path);
        }
        return back();
    }

    public function nginxConf(Project $project)
    {
        $nginxConfPath = Storage::disk('etc')->path('nginx\\sites-enabled\\' . $project->url . '.conf');
        if (File::exists($nginxConfPath)) {
            Process::run('explorer ' . $nginxConfPath);
            Notification::new()->title('Nginx configuration opened ')->show();
        } else {
            Notification::new()->title('Nginx configuration not opened')->show();
        }
        return redirect()->back();

    }

    public function delete(Project $project)
    {
        if (File::exists($project->path)) {
            if (Process::run('rmdir /s /q  ' . $project->path)->successful()) {
                Notification::new()->title('Delete Success')->message('The delete folder was successful.')->show();
            } else {
                Notification::new()->title('Delete Failed')->message('The delete folder failed.')->show();
            }

        } else {
            Notification::new()->title('Delete Failed')->message('The delete folder not found.')->show();
        }
        $projectService = new ProjectService($project);
        $projectService->projectDelete();
        Notification::new()->title('Project Delete Success')->message('The project was deleted successfully.')->show();
        return redirect()->route('projects.index');
    }

    public function https(Project $project)
    {
        $project->update([
            'is_secure' => !$project->is_secure
        ]);
        dispatch_sync(new ProjectConfJob($project));
        PHP::restart('nginx');
        Notification::new()->title('Project Update Success')->message('The project update nginx configuration was successful.')->show();
        return response()->json(['success' => true]);
    }

    public function favorite(Project $project)
    {
        $project->update([
            'is_favorite' => !$project->is_favorite
        ]);
        return response()->json(['success' => true]);
    }


    public function createPost(ProjectRequest $request)
    {
        if (File::exists($request->path) || Project::where('path', $request->path)->exists()) {
            Notification::new()->title('Create Failed')
                ->message('The project folder already exists.')->show();
            return redirect()->route('projects.create');
        } else {

            $project = Project::create($request->validated());
            switch ($request->type) {
                case ProjectType::PHP->value:
                case ProjectType::BLANK->value:
                    if (File::makeDirectory(Str::replace('/', '\\', $request->path), 0775, true)) {
                        $projectClass = new ProjectService($project);
                        $projectClass->projectCreate();
                        Notification::new()->title('Create Success')
                            ->message('The project was created successfully.')->show();
                    } else {
                        Notification::new()->title('Create Failed')
                            ->message('The project folder creation failed.')->show();
                    }
                    break;
                case ProjectType::VUE->value:
                case ProjectType::REACT->value:
                case ProjectType::ANGULAR->value:
                case ProjectType::SVELTE->value:
                case ProjectType::Nuxt->value:
                case ProjectType::VITE->value:
                case ProjectType::LARAVEL->value:
                case ProjectType::NEXT->value:
                    Notification::new()->title('Creating Project')
                        ->message('The ' . $project->type->label() . ' installation is in progress.')
                        ->show();
                    if (ProjectType::LARAVEL->value === $request->type) {
                        $process = Process::path(Str::replaceLast('\\' . $request->name, '', $project->path))
                            ->run('start cmd /k ' . $project->type->createCommand() . $request->name);

                        if ($process->successful()) {
                            Notification::new()->title('Create Success')
                                ->message('The ' . $project->type->label() . ' type project was created successfully.')
                                ->addAction('open')
                                ->show();
                        }


                    } else {

                        Process::path(Str::replaceLast('\\' . $request->name, '', $project->path))
                            ->run('start cmd /k ' .
                                Storage::disk('node')->path($request->node_version . '\\' . $project->type->createCommand()) . ' ' . $request->name
                            );
                    }
                    $projectClass = new ProjectService($project);
                    $projectClass->projectCreate();

                    Notification::new()->title('Create Success')
                        ->message('The ' . $project->type->label() . ' type project was created successfully.')
                        ->show();
                    break;
                default:
                    Notification::new()->title('Create Failed')
                        ->message('The project type does not exist.')
                        ->show();
                    return redirect()->route('projects.create');
                    break;
            }
            return redirect()->route('projects.index');
        }

    }

    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $exProject = $project;
        $basePath = $project->path;
        if ($project->update($request->validated())) {
            if ($project->path != $exProject->path) {
                File::moveDirectory($basePath, $project->path);
            }
            $projectService = new ProjectService($project);
            $projectService->projectConfCreate()->sslCertificateCreate();
            Notification::new()->title('Update Success')
                ->message('The project was updated successfully.')->show();
        }
        return redirect()->route('projects.show', $project);
    }

    public function start(string $project)
    {
        $project = Project::where('id', $project)->firstOrFail();
        if ($project->php_version == 'choose') {
            Notification::new()->title('Start Failed')
                ->message('The project not selected php version.')
                ->show();

        } else {
            $phpBVersion = PHPManagerService::modelData()->data;
            if ($phpBVersion == 'choose') {
                Notification::new()->title('Start Failed')
                    ->message('The project not selected php version.')
                    ->show();
            } else {
                $projectPath = $project->path;
                $phpPath = Storage::disk('php')->path($phpBVersion);
                if ($project->type == 'laravel') {
                    Process::run('start cmd /k ' . $phpPath . '\\php.exe -S 127.0.0.1:8000 -t ' . $projectPath . '\\public');
                } else {
                    Process::run('start cmd /k ' . $phpPath . '/php.exe -S 127.0.0.1:8000 -t ' . $projectPath);
                }
                Notification::new()->title('Start Success')
                    ->message('The project was started successfully.')
                    ->show();
            }

        }
    }

    public function terminal(Project $project, Request $request)
    {
        $command = $request->command;
        if (!empty($command)) {


            if (Str::startsWith($command, 'php')) {
                $command = Str::replace('php ', Storage::disk('php')->path($request->php) . '\\php.exe ', $command);
            } elseif (Str::startsWith($command, 'node')) {
                $command = Str::replace('node ', Storage::disk('node')->path($request->node) . '\\node.exe ', $command);
            } elseif (Str::startsWith($command, 'npm')) {
                $command = Str::replace('npm ', Storage::disk('node')->path($request->node) . '\\npm.cmd ', $command);
            }
        }

        $process = Process::path($project->path)->run($command);
        if ($process->successful()) {
            return response()->json([
                'success' => true,
                'command' => $request->input('command'),
                'output' => $process->output()]);
        } else {
            return response()->json([
                'success' => false,
                'command' => $request->input('command'),
                'output' => $process->errorOutput()]);
        }
    }

    public function ssl(Project $project)
    {
        $projectClass = new ProjectService($project);
        $projectClass->sslCertificateCreate();
        PHP::restart('nginx');

        return response()->json(['success' => true]);
    }
}
