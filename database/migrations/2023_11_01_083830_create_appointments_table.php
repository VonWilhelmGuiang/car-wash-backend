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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->foreignId('shop_owner_id')->references('shop_owner_id')->on('shop_owners')->constrained();
            $table->foreignId('vehicle_owner_id')->references('vehicle_owner_id')->on('vehicle_owners')->constrained();
            $table->timestamp('created_at')->useCurrent();
            $table->dateTime('appointment_schedule');
            $table->smallInteger('queue_number');
            $table->foreignId('service_id')->references('service_id')->on('shop_services')->constrained();
            $table->smallInteger('completed')
                ->comment('0:pending, 1:in-progress, 2:completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
