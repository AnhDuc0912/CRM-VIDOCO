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
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->integer('contract_type');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', [1, 2, 3])->default(1)->comment('1: Đang hợp đồng, 2: Hết hạn, 3: Chưa ký');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_contracts');
    }
};
