<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('signalements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reportable_id');
            $table->string('reportable_type');
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->cascadeOnDelete();
            $table->string('categorie')->nullable();
            $table->text('commentaire')->nullable();
            $table->enum('statut',['nouveau','en_cours','resolu','rejete'])->default('nouveau');
            $table->timestamps();
            $table->index(['reportable_id','reportable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('signalements');
    }
};
