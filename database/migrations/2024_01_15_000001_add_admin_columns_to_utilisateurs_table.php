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
        // Ajouter les colonnes manquantes à la table utilisateurs
        Schema::table('utilisateurs', function (Blueprint $table) {
            if (!Schema::hasColumn('utilisateurs', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('email_verified_at');
            }
            if (!Schema::hasColumn('utilisateurs', 'is_banned')) {
                $table->boolean('is_banned')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('utilisateurs', 'ban_reason')) {
                $table->text('ban_reason')->nullable()->after('is_banned');
            }
            if (!Schema::hasColumn('utilisateurs', 'banned_at')) {
                $table->timestamp('banned_at')->nullable()->after('ban_reason');
            }
            if (!Schema::hasColumn('utilisateurs', 'warning_count')) {
                $table->integer('warning_count')->default(0)->after('banned_at');
            }
            if (!Schema::hasColumn('utilisateurs', 'last_seen')) {
                $table->timestamp('last_seen')->nullable()->after('warning_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'is_banned',
                'ban_reason',
                'banned_at',
                'warning_count',
                'last_seen',
            ]);
        });
    }
};
