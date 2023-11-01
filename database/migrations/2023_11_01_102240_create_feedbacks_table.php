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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->foreignId('shop_owner_id')->references('shop_owner_id')->on('shop_owners')->constrained();
            $table->foreignId('vehicle_owner_id')->references('vehicle_owner_id')->on('vehicle_owners')->constrained();
            $table->foreignId('transaction_id')->references('transaction_id')->on('transactions')->constrained();
            $table->smallInteger('feeback_rate');
            $table->string('feeback_comment',150);
            $table->timestamp('created_on')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
