<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_bank_accounts', function (Blueprint $table) {
            $table->foreignId('ad_id')
                ->nullable()
                ->after('seller_id')
                ->constrained('ads')
                ->cascadeOnDelete();

            $table->unique('ad_id');
        });
    }

    public function down(): void
    {
        Schema::table('seller_bank_accounts', function (Blueprint $table) {
            $table->dropUnique(['ad_id']);
            $table->dropForeign(['ad_id']);
            $table->dropColumn('ad_id');
        });
    }
};
