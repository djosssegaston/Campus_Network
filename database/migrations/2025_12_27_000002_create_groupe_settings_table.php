<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('groupe_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupe_id')->unique()->constrained('groupes')->cascadeOnDelete();
            $table->boolean('moderation_requise')->default(false);
            $table->boolean('autoriser_messages')->default(true);
            $table->boolean('autoriser_publications')->default(true);
            $table->boolean('autoriser_medias')->default(true);
            $table->enum('permission_publication', ['tous', 'moderateurs', 'admin'])->default('tous');
            $table->enum('permission_message', ['tous', 'membres', 'admin'])->default('tous');
            $table->json('mots_cles_interdits')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('groupe_settings');
    }
};
