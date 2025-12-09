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
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('abstract');
            $table->text('keywords')->nullable();
            $table->string('doi')->nullable();
            $table->string('file_path'); // Path ke file PDF
            $table->string('original_filename'); // Nama file asli
            $table->enum('status', [
                'submitted',
                'under_review',
                'accepted',
                'revision_minor',
                'revision_major',
                'rejected',
                'published'
            ])->default('submitted');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('issue_id')->nullable()->constrained('issues')->onDelete('set null');
            $table->integer('page_from')->nullable();
            $table->integer('page_to')->nullable();
            $table->date('submitted_at')->nullable();
            $table->date('reviewed_at')->nullable();
            $table->date('published_at')->nullable();
            $table->integer('revision_count')->default(0);
            $table->timestamps();
            
            // Index
            $table->index('status');
            $table->index(['author_id', 'status']);
            $table->index(['issue_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('papers');
    }
};