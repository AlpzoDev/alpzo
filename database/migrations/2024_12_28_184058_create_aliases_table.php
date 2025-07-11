<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aliases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('command');
            $table->text('description')->nullable();
            $table->boolean('is_global');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aliases');
    }
};
