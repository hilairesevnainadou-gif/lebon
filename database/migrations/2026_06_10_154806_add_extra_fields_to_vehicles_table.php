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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('finish')->nullable()->after('gearbox');   // Finition Constructeur
            $table->string('version')->nullable()->after('finish');   // Version Constructeur
            $table->string('history')->nullable()->after('condition'); // Historique et entretien
            $table->string('license')->nullable()->after('history');  // Permis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['finish', 'version', 'history', 'license']);
        });
    }
};
