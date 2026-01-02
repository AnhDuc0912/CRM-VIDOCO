<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên cấp bậc (Lãnh đạo, Quản lý, Nhân viên...)
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->timestamps();
        });
    }

    public function down(): void {}
};
