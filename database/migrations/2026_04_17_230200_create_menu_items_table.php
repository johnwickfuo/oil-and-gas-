<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('photo')->nullable();
            $table->enum('category', [
                'protein', 'side', 'swallow', 'dessert', 'drink', 'small_chops',
            ]);
            $table->date('week_of');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['week_of', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
