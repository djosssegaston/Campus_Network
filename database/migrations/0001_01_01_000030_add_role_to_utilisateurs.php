<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations - Add role foreign key and indexes
     */
    public function up()
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            // Add foreign key constraint if not already present
            if (!Schema::hasColumn('utilisateurs', 'role_id')) {
                $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            }
            
            // Add index for faster role queries
            if (!Schema::hasColumn('utilisateurs', 'role_id')) {
                // Already added above
            } else {
                $table->index('role_id');
            }
        });
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            if (Schema::hasColumn('utilisateurs', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }
        });
    }
};
