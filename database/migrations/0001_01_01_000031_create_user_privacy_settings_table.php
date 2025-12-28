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
        Schema::create('user_privacy_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            
            // Visibilité du profil
            $table->enum('profil_visibilite', ['public', 'prive', 'amis'])->default('public');
            
            // Qui peut m'envoyer des messages
            $table->enum('messages_acceptes', ['tous', 'amis', 'personne'])->default('tous');
            
            // Qui peut voir mes publications
            $table->enum('publications_lisibles', ['public', 'amis', 'prive'])->default('public');
            
            // Qui peut commenter mes publications
            $table->enum('commentaires_acceptes', ['tous', 'amis', 'personne'])->default('tous');
            
            // Qui peut me voir dans les groupes
            $table->enum('groupes_visibles', ['public', 'prive'])->default('public');
            
            // Afficher ma liste d'amis/contacts
            $table->boolean('afficher_contacts')->default(true);
            
            // Afficher mes groupes
            $table->boolean('afficher_groupes')->default(true);
            
            // Afficher mon historique d'activité
            $table->boolean('afficher_activite')->default(false);
            
            // Autoriser les autres à me mentionner
            $table->boolean('mentions_autorisees')->default(true);
            
            // Notifier pour les nouvelles requêtes de contact
            $table->boolean('notifier_requetes_contact')->default(true);
            
            // Notifier pour les commentaires
            $table->boolean('notifier_commentaires')->default(true);
            
            // Notifier pour les réactions
            $table->boolean('notifier_reactions')->default(true);
            
            $table->timestamps();
            
            $table->unique('utilisateur_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_privacy_settings');
    }
};
