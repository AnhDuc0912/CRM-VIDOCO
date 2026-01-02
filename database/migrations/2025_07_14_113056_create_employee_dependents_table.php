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
        Schema::create('employee_dependents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('relationship', [1, 2, 3, 4, 5])->comment('1: Bố, 2: Mẹ, 3: Vợ, 4: Chồng, 5: Con Cái');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->date('birthday')->nullable();
            $table->string('job')->nullable();
            $table->enum('gender', [1, 2, 3])->default(1)->comment('1: Nam, 2: Nữ, 3: Khác');
            $table->bigInteger('employee_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_dependents');
    }
};
