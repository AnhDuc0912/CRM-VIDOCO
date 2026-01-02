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
        Schema::create('customer_notification_histories', function (Blueprint $table) {
            $table->id();

            $table->string('channel');
            $table->string('provider')->nullable();
            $table->string('to');

            $table->string('template_id')->nullable();
            $table->json('payload')->nullable();

            $table->string('status')->default('pending');
            $table->integer('error_code')->nullable();
            $table->text('error_message')->nullable();

            $table->json('response')->nullable();

            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_notifications_history');
    }
};
