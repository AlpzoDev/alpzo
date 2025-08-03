<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\PHPController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Services\AllServiceController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['isInstalled']], function () {

    Route::get('/test', [HomeController::class,'test'])->name('test');
    Route::get('/', [HomeController::class,'dashboard'])->name('dashboard');
    Route::get('/about', [HomeController::class,'about'])->name('about');
//    Route::get('/help', [HomeController::class,'help'])->name('help');
    Route::group([
        'prefix' => 'php-versions',
        'as' => 'php.'
    ], function () {
        Route::get('/', [PHPController::class, 'index'])->name('index');
        Route::group(['prefix'=>'all'], function () {
            Route::get('/stop', [PHPController::class, 'allStop'])->name('all.stop');
            Route::get('/restart', [PHPController::class, 'allRestart'])->name('all.restart');
            Route::get('/start', [PHPController::class, 'allStart'])->name('all.start');
        });
        Route::get('/create', [PHPController::class, 'create'])->name('create');
        Route::post('/install', [PHPController::class, 'installPHP'])->name('install');
        Route::get('/{phpVersion}/show-folder', [PHPController::class, 'showPHPFolder'])->name('show-folder');
        Route::get('/{phpVersion}/delete', [PHPController::class, 'removePHP'])->name('delete');
        Route::get('/{phpVersion}/change', [PHPController::class, 'changePHPVersion'])->name('change');
        Route::post('/{phpVersion}/reset-ini', [App\Http\Controllers\PHPController::class, 'phpIniReset'])->name('reset-ini');
        Route::post('/{phpVersion}/update-ini', [PHPController::class, 'updatePhpIni'])->name('update-ini');
        Route::post('/{phpVersion}/backup-ini', [PHPController::class, 'backupIni'])->name('backup-ini');
        Route::get('/{phpVersion}/start', [PHPController::class, 'phpStart'])->name('start');
        Route::get('/{phpVersion}/restart', [PHPController::class, 'phpRestart'])->name('restart');
        Route::get('/{phpVersion}/stop', [PHPController::class, 'phpStop'])->name('stop');
        Route::get('/{phpVersion}', [PHPController::class, 'show'])->name('show');
    });

    Route::group([
        'prefix' => 'node-versions',
        'as' => 'node.'
    ], function () {
        Route::get('/', [App\Http\Controllers\NodeController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\NodeController::class, 'create'])->name('create');
        Route::post('/install', [App\Http\Controllers\NodeController::class, 'installNode'])->name('install');
        Route::get('/{nodeVersion}/delete', [App\Http\Controllers\NodeController::class, 'deleteNodeVersion'])->name('delete');
        Route::get('/{nodeVersion}/change', [App\Http\Controllers\NodeController::class, 'changeNodeVersion'])->name('change');
        Route::get('/{nodeVersion}/show-folder', [App\Http\Controllers\NodeController::class, 'showNodeFolder'])->name('show-folder');
    });

    Route::group([
        'prefix' => 'services',
        'as' => 'services.'
    ], function () {
        Route::get('/all/stop', [AllServiceController::class, 'allStop'])->name('all.stop');
        Route::get('{service}/restart', [AllServiceController::class, 'restart'])->name('restart');
        Route::get('{service}/stop', [AllServiceController::class, 'stop'])->name('stop');
        Route::get('{service}/start', [AllServiceController::class, 'start'])->name('start');

    });
    Route::group([
        'prefix' => 'projects',
        'as' => 'projects.'
    ], function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::post('/', [ProjectController::class, 'createPost'])->name('create.post');
        Route::get('/{project}/show-folder', [ProjectController::class, 'showFolder'])->name('show-folder');
        Route::get('/{project}/nginx-conf', [ProjectController::class, 'nginxConf'])->name('nginx-conf');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::get('/{project}/delete', [ProjectController::class, 'delete'])->name('delete');
        Route::post('/{project}/update', [ProjectController::class, 'update'])->name('update');
        Route::get('/{project}/https', [ProjectController::class, 'https'])->name('https');
        Route::get('/{project}/favorite', [ProjectController::class, 'favorite'])->name('favorite');
        Route::get('/{project}/ssl', [ProjectController::class, 'ssl'])->name('ssl');
        Route::get('/{project}/start', [ProjectController::class, 'start'])->name('start');
        Route::post('/{project}/terminal', [ProjectController::class, 'terminal'])->name('terminal');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show');

    });

    Route::group([
        'prefix' => 'settings',
        'as' => 'settings.'
    ], function () {
        Route::get('/', App\Http\Controllers\Setting\IndexController::class)->name('index');

        Route::post('/path/create', [App\Http\Controllers\Setting\PathController::class, 'store'])->name('path');
        Route::post('/path/{path}/update', [App\Http\Controllers\Setting\PathController::class, 'update'])->name('path.update');
        Route::delete('/path/{path}/delete', [App\Http\Controllers\Setting\PathController::class, 'destroy'])->name('path.delete');
        Route::post('/path/{path}/default', [App\Http\Controllers\Setting\PathController::class, 'default'])->name('default');

        Route::post('/auto-virtual-host', [App\Http\Controllers\Setting\AutoVituralHostController::class, '__invoke'])->name('auto-virtual-host');
        Route::post('/host-name', [App\Http\Controllers\Setting\HostNameController::class, '__invoke'])->name('host-name');

//        Route::apiResource('terminal/aliases', App\Http\Controllers\AliasController::class);


    });


//    Route::group([
//        'prefix' => 'terminel',
//        'as' => 'terminel.'
//    ], function () {
//        Route::get('/', [App\Http\Controllers\TerminelController::class, 'index'])->name('index');
//        Route::get('/new', [App\Http\Controllers\TerminelController::class, 'new'])->name('new');
//    });

});

Route::group([
    'prefix' => 'menubar',
    'as' => 'menubar.'
], function () {
    Route::get('/', [App\Http\Controllers\Menubar\IndexController::class, 'index'])->name('index');
    Route::get('/php-versions', [App\Http\Controllers\Menubar\IndexController::class, 'phpVersions'])->name('php-versions');
});

Route::group([
    'prefix' => 'windows',
    'as' => 'windows.'
], function () {
    Route::get('/main', [App\Http\Controllers\WindowController::class, 'main'])->name('main');
});

Route::group([
    'prefix' => 'installer',
    'middleware' => 'isNotInstalled',
    'as' => 'install.'
], function () {
    Route::get('/', [App\Http\Controllers\InstallerController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\InstallerController::class, 'post'])->name('post');
});

Route::group([
    'prefix' => 'links',
    'as' => 'links.'
], function () {
    Route::get('/github/{repo}', [LinkController::class, 'github'])->name('github');
    Route::post('/url', [LinkController::class, 'url'])->name('url');
});
