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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('qr_code')->unique()->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name')->nullable();
            $table->string('email_work')->nullable();
            $table->string('email_personal')->nullable();
            $table->string('avatar')->nullable();
            $table->integer('level')->nullable();
            $table->string('citizen_id_number')->nullable();
            $table->date('citizen_id_created_date')->nullable();
            $table->string('citizen_id_created_place')->nullable();
            $table->enum('gender', [1, 2, 3])->default(1)->comment('1: Nam, 2: Nữ, 3: Khác');
            $table->string('education')->nullable();
            $table->string('phone');
            $table->string('permanent_address')->nullable();
            $table->string('current_address')->nullable();
            $table->date('birthday')->nullable();
            $table->integer('current_position')->nullable();
            $table->integer('last_position')->nullable();
            $table->date('start_date')->default(now());
            $table->bigInteger('manager_id')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
