<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt');
            $table->json('ingredients');
            $table->json('instructions');
            $table->string('cover_image')->nullable();
            $table->unsignedInteger('prep_time');
            $table->unsignedInteger('cook_time');
            $table->unsignedInteger('servings');
            $table->enum('difficulty', ['easy', 'medium', 'hard']);
            $table->string('meal_type');
            $table->dateTime('published_at')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->index('slug');
            $table->index('published_at');
            $table->index('meal_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
