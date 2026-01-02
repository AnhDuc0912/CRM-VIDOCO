<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('day_offs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->enum('session', ['AM', 'PM'])->default('AM');
            $table->enum('type', ['ke_hoach', 'lam_viec_o_nha', 'ngoai_le'])->nullable();
            $table->enum('reason_type', ['tac_duong', 'nghi_om', 'viec_khan_cap'])->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('mode', ['den_muon', 've_som', 'ra_ngoai'])->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->time('time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('day_offs');
    }
};
