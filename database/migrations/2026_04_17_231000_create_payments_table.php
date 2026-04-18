<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->enum('gateway', ['paystack', 'flutterwave']);
            $table->string('reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('NGN');
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])
                ->default('pending');
            $table->json('gateway_response')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
