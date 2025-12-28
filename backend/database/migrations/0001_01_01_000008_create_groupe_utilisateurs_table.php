<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration : pivot groupe_utilisateurs
class CreateGroupeUtilisateursTable extends Migration
{
    public function up()
    {
        Schema::create('groupe_utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupe_id')->constrained('groupes')->onDelete('cascade');
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->enum('role', ['membre', 'moderateur', 'admin'])->default('membre');
            $table->timestamp('rejoins_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('groupe_utilisateurs');
    }
}
