<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sell_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('sell_contract_id')->nullable()->after('proposal_id');
        });
    }

    public function down(): void
    {
        Schema::table('sell_orders', function (Blueprint $table) {
            $table->dropColumn('sell_contract_id');
        });
    }
};
