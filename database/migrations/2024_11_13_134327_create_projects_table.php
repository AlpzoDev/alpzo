<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('path')->nullable();
            $table->string('url')->nullable();
            $table->string('php_version')->nullable();
            $table->string('node_version')->nullable();
            $table->string('type');
            $table->boolean('is_secure')->default(false);
            $table->boolean('is_favorite')->default(false);
            $table->string('server')->nullable()->default('nginx');// nginx, apache
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
