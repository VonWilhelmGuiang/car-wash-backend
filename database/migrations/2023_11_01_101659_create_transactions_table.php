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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->string('transaction_uid')->unique();
            $table->foreignId('shop_owner_id')->references('shop_owner_id')->on('shop_owners')->constrained();
            $table->foreignId('vehicle_owner_id')->references('vehicle_owner_id')->on('vehicle_owners')->constrained();
            $table->foreignId('appointments_id')->references('appointment_id')->on('appointments')->constrained();
            $table->float('amount',6,2);
            $table->timestamp('completed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};