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
        Schema::create('participant_quiz', function (Blueprint $table) {
            $table->foreignUuid('participant_id')->constrained();
            $table->foreignUuid('quiz_id')->constrained();
            $table->integer('highest_score')->nullable();
            $table->integer('attempt_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_quiz');
    }
};
