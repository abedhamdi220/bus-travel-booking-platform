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
        Schema::create('trip_updates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('reason_code',['MECH','Traffic_accidents','bad_weather_conditions','security issues','road_closer'])->nullable();
            $table->string('reason_notes')->nullable();
            $table->foreignId('trip_id')->constrained('trips','id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_updates');
    }
};
