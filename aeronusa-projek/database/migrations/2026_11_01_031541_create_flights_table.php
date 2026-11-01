<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_no', 20)->unique();
            $table->string('airline');
            $table->string('aircraft');
            $table->string('from_code', 3);
            $table->string('to_code', 3);
            $table->string('dep_time', 10);
            $table->string('arr_time', 10);
            $table->string('duration', 20);
            $table->string('type')->default('Langsung');
            $table->string('class')->default('Ekonomi');
            $table->unsignedBigInteger('base_price');
            $table->integer('free_baggage')->default(20);
            $table->string('badge')->nullable();
            $table->json('occupied_seats')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};