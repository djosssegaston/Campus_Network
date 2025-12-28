<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('groupe_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupe_id')->constrained('groupes')->cascadeOnDelete();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->cascadeOnDelete();
            $table->longText('contenu')->nullable();
            $table->enum('type', ['text', 'image', 'video', 'audio', 'fichier'])->default('text');
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour performance
            $table->index(['groupe_id', 'created_at']);
            $table->index('utilisateur_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('groupe_messages');
    }
};
