<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('groupe_utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupe_id')->constrained('groupes')->cascadeOnDelete();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->cascadeOnDelete();
            $table->enum('role',['membre','moderateur','admin'])->default('membre');
            $table->timestamps();
            $table->unique(['groupe_id','utilisateur_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('groupe_utilisateurs');
    }
};
