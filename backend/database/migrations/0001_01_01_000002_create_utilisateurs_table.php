<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration : création de la table des utilisateurs (Utilisateurs)
class CreateUtilisateursTable extends Migration
{
    public function up()
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->comment('Nom complet de l\'utilisateur');
            $table->string('email')->unique()->comment('Adresse e-mail');
            $table->timestamp('email_verified_at')->nullable()->comment('Date de vérification e-mail');
            $table->string('mot_de_passe')->nullable()->comment('Mot de passe (haché)');
            $table->string('filiere')->nullable()->comment('Filière / département');
            $table->string('annee_etude')->nullable()->comment('Année d\'étude');
            $table->string('avatar_url')->nullable()->comment('URL de l\'avatar');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('utilisateurs');
    }
}
