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
            $table->id('vehicle_info_id');
            $table->foreignId('vehicle_owner_id')->references('vehicle_owner_id')->on('vehicle_owners')->constrained();
            $table->string('model',30);
            $table->string('license_plate',30)->unique();
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
