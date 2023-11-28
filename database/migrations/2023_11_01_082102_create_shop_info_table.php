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
        Schema::create('shop_info', function (Blueprint $table) {
            $table->id('shop_info_id');
            $table->foreignId('shop_owner_id')->references('shop_owner_id')->on('shop_owners')->constrained();
            $table->string('name',100);
            $table->string('location')->comment('longitude and latitude');
            $table->string('operating_from',5);
            $table->string('operating_to',5);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_info');
    }
};
