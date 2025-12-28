<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('model_id');
            $table->string('model_type');
            $table->string('nom_fichier');
            $table->string('chemin');
            $table->string('type_mime')->nullable();
            $table->bigInteger('taille')->nullable();
            $table->timestamps();
            $table->index(['model_id','model_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('medias');
    }
};
