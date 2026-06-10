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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->enum('payment_type', ['DP', 'LUNAS']);
            $table->enum('payment_method', ['QRIS'])->default('QRIS');
            $table->unsignedInteger('amount');
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED', 'EXPIRED'])->default('PENDING');
            $table->string('gateway_reference')->nullable()->index();
            $table->text('qr_payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('callback_payload')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
