<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt');
            $table->longText('body');
            $table->string('cover_image')->nullable();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->string('category');
            $table->json('tags')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->index('slug');
            $table->index('published_at');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
