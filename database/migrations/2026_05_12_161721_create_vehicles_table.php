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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // قمنا بإزالة unique() من هنا
            $table->integer('vehicle_number');

            $table->enum('type_vehicle',['VIP','nurmal']);
            $table->date('date_work');
            $table->string('plate_number');
            $table->enum('state',['availability','maintenance','on_trip','out_of_service'])->default('out_of_service');
            $table->foreignId('company_id')->constrained('companies','id')->cascadeOnDelete();

            // إضافة القيد المزدوج: رقم المركبة يكون فريداً داخل نفس الشركة فقط
            $table->unique(['company_id', 'vehicle_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
