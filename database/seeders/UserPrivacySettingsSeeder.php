<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use App\Models\UserPrivacySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserPrivacySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les paramètres de confidentialité par défaut pour tous les utilisateurs
        $utilisateurs = Utilisateur::whereNull('privacy_settings_id')->get();

        foreach ($utilisateurs as $utilisateur) {
            UserPrivacySetting::create([
                'utilisateur_id' => $utilisateur->id,
                'profil_visibilite' => 'public',
                'messages_acceptes' => 'tous',
                'publications_lisibles' => 'public',
                'commentaires_acceptes' => 'tous',
                'groupes_visibles' => 'public',
                'afficher_contacts' => true,
                'afficher_groupes' => true,
                'afficher_activite' => false,
                'mentions_autorisees' => true,
                'notifier_requetes_contact' => true,
                'notifier_commentaires' => true,
                'notifier_reactions' => true,
            ]);
        }
    }
}
