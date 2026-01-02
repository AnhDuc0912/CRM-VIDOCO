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
        Schema::create('customer_behaviors', function (Blueprint $table) {
            $table->id();
            // Thời điểm & Liên hệ
            $table->integer('preferred_contact_time')->nullable();
            $table->json('contact_methods')->nullable();
            $table->string('other_contact_method')->nullable();

            // Thói quen tiêu dùng
            $table->json('consumption_habits')->nullable();
            $table->integer('purchase_decision_time')->nullable();
            $table->integer('price_sensitivity')->nullable();
            $table->integer('purchase_influencer')->nullable();

            // Tâm lý & Tính cách
            $table->integer('personality_traits')->nullable();
            $table->json('attention_points')->nullable();
            $table->string('other_attention_point')->nullable();
            $table->json('preferences')->nullable();

            // Cách xưng hô phù hợp
            $table->integer('salutation')->nullable();

            // Phản ứng đặc biệt đã từng gặp
            $table->json('special_reactions')->nullable();
            $table->string('other_special_reaction')->nullable();

            // Cảm xúc thường gặp khi trao đổi
            $table->integer('common_emotion')->nullable();

            // Sở thích cá nhân
            $table->text('personal_interests')->nullable();
            $table->text('topics_of_interest')->nullable();
            $table->text('religious_political_views')->nullable();

            $table->bigInteger('customer_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_behaviors');
    }
};
