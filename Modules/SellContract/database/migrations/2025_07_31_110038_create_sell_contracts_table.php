<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\SellContract\Enums\SellContractStatusEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sell_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('status')->default(SellContractStatusEnum::NEW);
            $table->double('amount');
            $table->text('note')->nullable();
            $table->date('expired_at');
            $table->bigInteger('proposal_id')->nullable();
            $table->bigInteger('created_by');
            $table->bigInteger('customer_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_contracts');
    }
};
