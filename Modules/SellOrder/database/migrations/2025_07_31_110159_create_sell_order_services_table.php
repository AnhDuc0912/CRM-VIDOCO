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
        Schema::create('sell_order_services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sell_order_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->string('price')->nullable();
            $table->string('quantity')->nullable();
            $table->string('total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_order_services');
    }
};
