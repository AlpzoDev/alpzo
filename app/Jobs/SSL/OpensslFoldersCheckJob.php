<?php

namespace App\Jobs\SSL;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OpensslFoldersCheckJob implements ShouldQueue
{
use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

public function __construct()
{
}

public function handle(): void
{

if (!File::exists(Storage::disk('etc')->path('ssl')))
{
File::makeDirectory(Storage::disk('etc')->path('ssl'),0775,true);
}

if (!File::exists(Storage::disk('etc')->path('ssl\\certs')))
{
File::makeDirectory(Storage::disk('etc')->path('ssl\\certs'),0775,true);
}

if (!File::exists(Storage::disk('etc')->path('ssl\\private')))
{
File::makeDirectory(Storage::disk('etc')->path('ssl\\private'),0775,true);
}
}
}
