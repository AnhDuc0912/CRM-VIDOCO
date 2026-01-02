<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code_template')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->unsignedBigInteger('type_id');
            $table->string('code')->nullable();

            $table->json('structures')->nullable();

            $table->integer('from_unit')->nullable();
            $table->json('recipients')->nullable();
            $table->text('aa')->nullable();
            $table->json('sender')->nullable();
            $table->json('to_internals')->nullable();
            $table->json('followers')->nullable();
            $table->json('receivers')->nullable();
            $table->integer('contract_type')->nullable();
            $table->text('content')->nullable();
            $table->text('bonus')->nullable();
            $table->integer('status')->default('0');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->boolean('send_mail')->default(false);
            $table->date('issue_date')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
        Schema::dropIfExists('document_types');
    }
};
