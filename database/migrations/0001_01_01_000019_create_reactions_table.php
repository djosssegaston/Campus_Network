<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->cascadeOnDelete();
            $table->unsignedBigInteger('reactable_id');
            $table->string('reactable_type');
            $table->string('type'); // like, love, sad, etc.
            $table->timestamps();
            $table->index(['reactable_id','reactable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reactions');
    }
};
