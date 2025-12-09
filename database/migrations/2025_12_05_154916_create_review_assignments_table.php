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
        Schema::create('review_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paper_id')->constrained('papers')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'declined', 'completed'])->default('pending');
            $table->date('assigned_date');
            $table->date('due_date');
            $table->date('completed_date')->nullable();
            $table->text('editor_notes')->nullable(); // Catatan dari editor untuk reviewer
            $table->timestamps();
            
            // Satu reviewer tidak boleh dapat paper yang sama dua kali
            $table->unique(['paper_id', 'reviewer_id']);
            
            // Index untuk query yang sering
            $table->index(['reviewer_id', 'status']);
            $table->index(['paper_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_assignments');
    }
};