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
        Schema::create('vehicle_info', function (Blueprint $table) {
            $table->id();
            $table->string('TicketNumber')->unique();
            $table->string('VehicleMake')->nullable();
            $table->string('PlateNumber')->nullable();
            $table->string('DriverName')->nullable();
            $table->bigInteger('DriverNumber')->nullable();
            $table->timestamp('EntryTime')->useCurrent();
            $table->timestamp('ExitTime')->nullable();
            $table->string('ParkingFee')->nullable();
            $table->mediumText('Remark')->nullable();
            $table->string('Status')->default('Parked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_info');
    }
};
