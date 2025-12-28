<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration : création de la table des rôles
class CreateRolesTable extends Migration
{
    // Exécution de la migration
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->comment('Nom lisible du rôle');
            $table->string('slug')->unique()->comment('Identifiant technique du rôle');
            $table->integer('niveau')->default(0)->comment('Niveau hiérarchique du rôle');
            $table->json('permissions')->nullable()->comment('Permissions JSON');
            $table->timestamps();
        });
    }

    // Annulation de la migration
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
