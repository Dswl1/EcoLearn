<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // 'flashcard' or 'quiz'
            $table->unsignedBigInteger('item_id'); // flashcard.id or quiz.id
            $table->string('status')->default('pending'); // 'mastered', 'reviewing', 'passed', 'failed'
            $table->integer('score')->nullable();
            $table->integer('attempts')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'type', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
