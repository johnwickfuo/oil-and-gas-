<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->date('event_date');
            $table->time('event_time');
            $table->unsignedInteger('guests');
            $table->string('location');
            $table->text('dietary_notes')->nullable();
            $table->text('special_requests')->nullable();
            $table->decimal('estimated_total', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->enum('status', [
                'pending_payment', 'confirmed', 'completed', 'cancelled',
            ])->default('pending_payment');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])
                ->default('unpaid');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('event_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
