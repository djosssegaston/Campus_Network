<?php
/**
 * TEST: Vérifier l'affichage des médias
 * Vérifie que le lien symbolique et les chemins fonctionnent
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Publication;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

echo "\n===== VÉRIFICATION DE L'AFFICHAGE DES MÉDIAS =====\n\n";

// 1. Vérifier le lien symbolique
echo "📁 VÉRIFICATION DU LIEN SYMBOLIQUE:\n";
$storageLink = public_path('storage');
if (is_link($storageLink)) {
    $target = readlink($storageLink);
    echo "✅ Lien symbolique existe\n";
    echo "   Source: " . $storageLink . "\n";
    echo "   Cible: " . $target . "\n\n";
} else {
    echo "❌ Lien symbolique N'EXISTE PAS - Les médias ne s'afficheront pas!\n";
    echo "   Commande: php artisan storage:link\n\n";
    exit(1);
}

// 2. Vérifier les fichiers physiques
echo "📂 VÉRIFICATION DES FICHIERS STOCKÉS:\n";
$medias = Media::all();
if ($medias->count() === 0) {
    echo "⚠️  Aucun média en base de données\n\n";
} else {
    echo "Nombre de médias: " . $medias->count() . "\n\n";
    
    foreach ($medias->take(3) as $media) {
        echo "📄 Média ID: " . $media->id . "\n";
        echo "   Nom: " . $media->nom_fichier . "\n";
        echo "   Chemin DB: " . $media->chemin . "\n";
        
        $fullPath = storage_path('app/public/' . $media->chemin);
        if (file_exists($fullPath)) {
            echo "   ✅ Fichier EXISTE\n";
            echo "   Taille: " . round(filesize($fullPath) / 1024, 2) . " KB\n";
        } else {
            echo "   ❌ Fichier N'EXISTE PAS: " . $fullPath . "\n";
        }
        
        // URL d'accès
        $publicUrl = asset('storage/' . $media->chemin);
        echo "   URL (asset): " . $publicUrl . "\n";
        
        // URL via Storage::url()
        $storageUrl = Storage::url($media->chemin);
        echo "   URL (Storage): " . $storageUrl . "\n\n";
    }
}

// 3. Vérifier les relations
echo "🔗 VÉRIFICATION DES RELATIONS POLYMORPHIQUES:\n";
$publications = Publication::with('medias')->take(3)->get();
echo "Nombre de publications avec médias: " . $publications->count() . "\n\n";

foreach ($publications as $pub) {
    if ($pub->medias->count() > 0) {
        echo "Publication ID: " . $pub->id . "\n";
        echo "Médias liés: " . $pub->medias->count() . "\n";
        foreach ($pub->medias as $media) {
            echo "  - " . $media->nom_fichier . " (" . $media->type_mime . ")\n";
        }
        echo "\n";
    }
}

echo "✅ VÉRIFICATION COMPLÈTE!\n\n";
