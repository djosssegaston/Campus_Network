<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration : publications
class CreatePublicationsTable extends Migration
{
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('groupe_id')->nullable()->constrained('groupes')->onDelete('cascade');
            $table->text('contenu')->nullable()->comment('Contenu texte de la publication');
            $table->enum('visibilite', ['publique', 'amis', 'groupe', 'prive'])->default('publique');
            $table->enum('statut', ['actif', 'supprime', 'signale'])->default('actif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('publications');
    }
}
