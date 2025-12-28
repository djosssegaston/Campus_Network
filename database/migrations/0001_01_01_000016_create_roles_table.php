<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Création de la table des rôles.
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // nom lisible
            $table->string('slug')->unique(); // identifiant technique
            $table->integer('niveau')->default(0); // priorité/hiérarchie
            $table->json('permissions')->nullable(); // permissions JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
