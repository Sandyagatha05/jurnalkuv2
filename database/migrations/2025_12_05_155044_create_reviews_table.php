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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('review_assignments')->onDelete('cascade');
            $table->text('comments_to_editor'); // Komentar untuk editor (confidential)
            $table->text('comments_to_author'); // Komentar untuk author
            $table->enum('recommendation', [
                'accept',
                'minor_revision',
                'major_revision',
                'reject'
            ]);
            $table->string('attachment_path')->nullable(); // File review tambahan
            $table->integer('originality_score')->nullable(); // 1-5
            $table->integer('contribution_score')->nullable(); // 1-5
            $table->integer('clarity_score')->nullable(); // 1-5
            $table->integer('methodology_score')->nullable(); // 1-5
            $table->integer('overall_score')->nullable(); // 1-5
            $table->boolean('is_confidential')->default(true);
            $table->date('reviewed_at');
            $table->timestamps();
            
            // Satu assignment hanya punya satu review
            $table->unique('assignment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};