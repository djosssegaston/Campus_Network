<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use App\Models\Publication;
use App\Models\Commentaire;
use App\Models\Reaction;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Créer 5 utilisateurs de test
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $user = Utilisateur::create([
                'nom' => "Utilisateur Test $i",
                'email' => "testuser$i@example.com",
                'mot_de_passe' => 'password123',
                'filiere' => collect(['Informatique', 'Électronique', 'Génie Civil', 'Médecine', 'Droit'])->random(),
                'annee_etude' => rand(1, 3),
            ]);
            $users[] = $user;
            $this->command->info("✓ Utilisateur créé: {$user->nom}");
        }

        // Créer 10 publications
        $publications = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = collect($users)->random();
            $publication = Publication::create([
                'utilisateur_id' => $user->id,
                'contenu' => "Publication de test #$i.\n\nCeci est un contenu de démonstration pour tester le fil d'actualités et vérifier que tout fonctionne correctement.",
                'visibilite' => collect(['public', 'amis', 'prive'])->random(),
                'statut' => 'actif',
            ]);
            $publications[] = $publication;
            $this->command->info("✓ Publication créée: ID {$publication->id} par {$user->nom}");
        }

        // Ajouter des réactions
        foreach ($publications as $publication) {
            $reactionCount = rand(2, 5);
            for ($j = 0; $j < $reactionCount; $j++) {
                $reactor = collect($users)->random();
                $existing = $publication->reactions()->where('utilisateur_id', $reactor->id)->first();
                
                if (!$existing) {
                    $reactionType = collect(['like', 'love', 'haha', 'wow', 'sad', 'angry'])->random();
                    $publication->reactions()->create([
                        'utilisateur_id' => $reactor->id,
                        'type' => $reactionType,
                    ]);
                }
            }
        }
        $this->command->info("✓ Réactions ajoutées");

        // Créer des commentaires
        for ($i = 1; $i <= 8; $i++) {
            $publication = collect($publications)->random();
            $user = collect($users)->random();
            
            $comment = Commentaire::create([
                'publication_id' => $publication->id,
                'utilisateur_id' => $user->id,
                'contenu' => "Commentaire de test #$i: C'est un très bon commentaire!",
            ]);
            
            // Ajouter quelques réactions aux commentaires aussi
            if (rand(0, 1)) {
                $reactor = collect($users)->random();
                $comment->reactions()->create([
                    'utilisateur_id' => $reactor->id,
                    'type' => collect(['like', 'love', 'haha'])->random(),
                ]);
            }
        }
        $this->command->info("✓ Commentaires créés");

        $this->command->info("\n✅ Données de test générées avec succès!");
        $this->command->info("Total: 5 utilisateurs, 10 publications, 8 commentaires, réactions");
    }
}
