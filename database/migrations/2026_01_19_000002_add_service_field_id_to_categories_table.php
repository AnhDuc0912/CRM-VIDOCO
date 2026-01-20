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
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('service_field_id')->nullable()->after('code');
            // optional foreign key if you want referential integrity
            // $table->foreign('service_field_id')->references('id')->on('service_fields')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // $table->dropForeign(['service_field_id']);
            $table->dropColumn('service_field_id');
        });
    }
};
