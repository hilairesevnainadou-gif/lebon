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
        Schema::create('vehicles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('ad_id')->constrained()->cascadeOnDelete();
    $table->string('brand');
    $table->string('model');
    $table->year('year');
    $table->unsignedInteger('mileage');
    $table->string('fuel_type');        // diesel, essence, hybride…
    $table->string('gearbox');          // manuelle, automatique
    $table->unsignedTinyInteger('doors')->nullable();
    $table->unsignedTinyInteger('seats')->nullable();
    $table->string('body_type')->nullable();     // berline, SUV…
    $table->string('color')->nullable();
    $table->unsignedSmallInteger('din_power')->nullable();
    $table->unsignedSmallInteger('fiscal_power')->nullable();
    $table->unsignedTinyInteger('critair')->nullable();
    $table->string('upholstery')->nullable();    // cuir, tissu…
    $table->date('first_registration_date')->nullable();
    $table->string('condition')->nullable();     // excellent, bon état…
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
