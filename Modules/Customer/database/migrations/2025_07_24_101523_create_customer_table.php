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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('customer_type')->default(1);
            $table->string('source_customer')->nullable();
            $table->unsignedBigInteger('person_incharge')->nullable();
            $table->unsignedBigInteger('sales_person')->nullable();

            // Công ty
            $table->string('company_name')->nullable();
            $table->string('tax_code')->nullable();
            $table->date('founding_date')->nullable();
            $table->string('company_address')->nullable();

            // Cá nhân
            $table->string('salutation')->nullable();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->date('birthday')->nullable();
            $table->string('identity_card')->nullable();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();

            // Chung
            $table->string('phone')->nullable();
            $table->string('sub_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('sub_email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('zalo')->nullable();
            $table->text('note')->nullable();

            // Xuất hóa đơn
            $table->string('invoice_name')->nullable();
            $table->string('invoice_tax_code')->nullable();
            $table->string('invoice_email')->nullable();

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
        Schema::dropIfExists('customers');
    }
};
