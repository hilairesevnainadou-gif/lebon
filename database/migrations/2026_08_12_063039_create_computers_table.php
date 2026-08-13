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
        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->constrained()->cascadeOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->string('cpu');
            $table->unsignedSmallInteger('ram_gb');
            $table->string('storage_type');   // ssd, hdd
            $table->unsignedInteger('storage_gb');
            $table->string('gpu')->nullable();
            $table->decimal('screen_size', 4, 1)->nullable(); // pouces, null pour un desktop
            $table->string('os')->nullable();
            $table->string('condition')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computers');
    }
};
