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
        Schema::create('shop_owners', function (Blueprint $table) {
            $table->id('shop_owner_id');
            $table->foreignId('account_id')->references('account_id')->on('accounts')->constrained();
            $table->string('shop_id',20)->unique();
            $table->string('first_name',50);
            $table->string('last_name',50);
            $table->string('phone',20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_owners');
    }
};
