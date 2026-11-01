<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('pnr_code', 6)->unique();
            $table->foreignId('flight_id')->constrained('flights')->cascadeOnDelete();
            $table->string('passenger_name');
            $table->string('passenger_email');
            $table->date('flight_date');
            $table->integer('pax_count')->default(1);
            $table->json('selected_seats');
            $table->integer('extra_baggage_kg')->default(0);
            $table->unsignedBigInteger('baggage_price')->default(0);
            $table->string('promo_code')->nullable();
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('grand_total');
            $table->enum('status', ['confirmed', 'refund_requested', 'cancelled'])->default('confirmed');
            $table->text('refund_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};