<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_photos', function (Blueprint $table) {
            $table->dropColumn('url');

            $table->string('disk')->default('public')->after('ad_id');
            $table->string('path')->after('disk');
            $table->string('original_name')->nullable()->after('path');
            $table->string('mime_type', 100)->nullable()->after('original_name');
            $table->unsignedInteger('size')->nullable()->after('mime_type');
        });
    }

    public function down(): void
    {
        Schema::table('ad_photos', function (Blueprint $table) {
            $table->dropColumn(['disk', 'path', 'original_name', 'mime_type', 'size']);
            $table->string('url')->after('ad_id');
        });
    }
};
