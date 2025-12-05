<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->integer('volume');
            $table->integer('number');
            $table->integer('year');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('published_date')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->foreignId('editor_id')->nullable()->constrained('users');
            $table->timestamps();
            
            // Index untuk pencarian cepat
            $table->index(['volume', 'number', 'year']);
            $table->unique(['volume', 'number', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};