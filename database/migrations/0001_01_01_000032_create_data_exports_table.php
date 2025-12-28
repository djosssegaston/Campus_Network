<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            
            // Informations sur l'export
            $table->enum('format', ['json', 'csv', 'zip'])->default('json');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            
            // Chemin du fichier stocké
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            
            // Informations de traitement
            $table->integer('total_items')->default(0);
            $table->integer('processed_items')->default(0);
            $table->string('error_message')->nullable();
            
            // Dates d'expiration (32 jours selon RGPD)
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('downloaded_at')->nullable();
            
            $table->timestamps();
            
            // Index pour recherche rapide
            $table->index(['utilisateur_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_exports');
    }
};
