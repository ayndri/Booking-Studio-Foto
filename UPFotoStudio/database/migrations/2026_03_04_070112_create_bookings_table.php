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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('guest_id')->constrained()->cascadeOnDelete();
            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_package_id')->constrained()->cascadeOnDelete();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('add_on_amount')->default(0);
            $table->unsignedInteger('total_amount');
            $table->enum('payment_type', ['DP', 'LUNAS']);
            $table->enum('status', ['PENDING_PAYMENT', 'CONFIRMED', 'CANCELLED', 'COMPLETED'])->default('PENDING_PAYMENT');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['studio_id', 'booking_date']);
            $table->index(['status', 'booking_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
