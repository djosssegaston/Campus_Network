<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration : signalements et modération
class CreateSignalementsTable extends Migration
{
    public function up()
    {
        Schema::create('signalements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->morphs('signalable');
            $table->string('categorie')->nullable();
            $table->text('raison')->nullable();
            $table->enum('statut', ['ouvert', 'en_attente', 'traite', 'refuse'])->default('ouvert');
            $table->foreignId('traite_par')->nullable()->constrained('utilisateurs')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('signalements');
    }
}
