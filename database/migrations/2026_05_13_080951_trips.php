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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('cost');
            $table->string('destination');
            $table->string('departure_city');
            $table->enum('state',['scheduled','in_progress','completed','cancelled','suspended'])->default('scheduled');
            $table->time('break_duration')->nullable();
            $table->enum('trip_type',['one_way','round_trip']);
            $table->json('days')->nullable();
            $table->enum('recurrence',['daily','weekly','one_time']);
            $table->dateTime('dateTrip');
            $table->time('timeTrip');
            $table->integer('totalSeats');
            $table->foreignId('company_id')->constrained('companies', 'id')->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('drivers','id');
            $table->foreignId('vehicle_id')->constrained('vehicles','id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
