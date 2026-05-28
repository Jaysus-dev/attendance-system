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
        Schema::create('class_assignments', function (Blueprint $table) {
    $table->id();

      $table->foreignId('class_assignment_id')
            ->nullable()
            ->constrained()
            ->onDelete('cascade');

    $table->foreignId('teacher_id')
        ->constrained()
        ->onDelete('cascade');

    $table->foreignId('course_id')
        ->constrained()
        ->onDelete('cascade');

    $table->foreignId('section_id')
        ->constrained()
        ->onDelete('cascade');

    $table->foreignId('subject_id')
        ->constrained()
        ->onDelete('cascade');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_assignments');
    }
};
