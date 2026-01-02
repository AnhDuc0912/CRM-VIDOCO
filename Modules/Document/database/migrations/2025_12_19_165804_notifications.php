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

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('from_user');
            $table->integer('to_user');
            $table->nullableMorphs('notifiable');

            $table->string('title');
            $table->text('content')->nullable();
            $table->string('url')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->string('type')->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
