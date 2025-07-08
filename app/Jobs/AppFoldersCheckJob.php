<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AppFoldersCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        if (!File::exists('C:\\alpzo')) {
            File::makeDirectory('C:\\alpzo', 0775, true);
        }

        if (!File::exists('C:\\alpzo\\bin')) {
            File::makeDirectory('C:\\alpzo\\bin', 0775, true);
        }

        if (!File::exists('C:\\alpzo\\etc')) {
            File::makeDirectory('C:\\alpzo\\etc', 0775, true);
        }

        if (!File::exists('C:\\alpzo\\etc\\nginx')) {
            File::makeDirectory('C:\\alpzo\\etc\\nginx', 0775, true);
        }

        if (!File::exists('C:\\alpzo\\etc\\nginx\\sites-enabled')) {
            File::makeDirectory('C:\\alpzo\\etc\\nginx\\sites-enabled', 0775, true);
        }

        if (!File::exists(Storage::disk('www')->path(''))) {
            File::makeDirectory('C:\\alpzo\\www', 0775, true);
        }
    }
}
