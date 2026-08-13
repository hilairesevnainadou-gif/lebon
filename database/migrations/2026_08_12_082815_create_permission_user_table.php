<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permission_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->primary(['user_id', 'permission_id']);
        });

        // Reprend les permissions déjà accordées via le rôle de chaque utilisateur,
        // pour que ce pivot devienne la seule source de vérité sans rien casser.
        $grants = DB::table('users')
            ->join('permission_role', 'users.role_id', '=', 'permission_role.role_id')
            ->whereNotNull('users.role_id')
            ->select('users.id as user_id', 'permission_role.permission_id')
            ->get();

        if ($grants->isNotEmpty()) {
            DB::table('permission_user')->insertOrIgnore(
                $grants->map(fn ($g) => ['user_id' => $g->user_id, 'permission_id' => $g->permission_id])->all()
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_user');
    }
};
