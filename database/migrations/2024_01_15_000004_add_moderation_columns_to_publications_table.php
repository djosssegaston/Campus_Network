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
        // Ajouter les colonnes manquantes à la table publications
        Schema::table('publications', function (Blueprint $table) {
            if (!Schema::hasColumn('publications', 'is_flagged')) {
                $table->boolean('is_flagged')->default(false)->after('type');
            }
            if (!Schema::hasColumn('publications', 'is_hidden')) {
                $table->boolean('is_hidden')->default(false)->after('is_flagged');
            }
            if (!Schema::hasColumn('publications', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable()->after('is_hidden');
            }
            if (!Schema::hasColumn('publications', 'view_count')) {
                $table->integer('view_count')->default(0)->after('scheduled_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn([
                'is_flagged',
                'is_hidden',
                'scheduled_at',
                'view_count',
            ]);
        });
    }
};
