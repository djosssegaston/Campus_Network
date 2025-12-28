<?php
/**
 * TEST: Affichage des médias
 * Teste la route de servage des fichiers et les URLs générées
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Publication;
use App\Models\Media;

echo "\n===== TEST AFFICHAGE MÉDIAS =====\n\n";

// 1. Vérifier le helper
echo "📝 TEST DU HELPER media_url():\n";
$testPath = "medias/test_2024_12345.jpg";
$mediaUrl = media_url($testPath);
echo "Chemin: " . $testPath . "\n";
echo "URL générée: " . $mediaUrl . "\n";
echo "Commence par /storage/: " . (str_starts_with($mediaUrl, '/storage/') ? "✅ OUI" : "❌ NON") . "\n\n";

// 2. Vérifier les médias en base
echo "📊 VÉRIFICATION DES MÉDIAS EN BASE:\n";
$medias = Media::all();
echo "Total de médias: " . $medias->count() . "\n\n";

if ($medias->count() > 0) {
    foreach ($medias->take(5) as $media) {
        echo "Média ID " . $media->id . ":\n";
        echo "  Nom: " . $media->nom_fichier . "\n";
        echo "  Chemin DB: " . $media->chemin . "\n";
        
        // Vérifier que le fichier existe
        $filePath = storage_path('app/public/' . $media->chemin);
        echo "  Fichier existe: " . (file_exists($filePath) ? "✅ OUI" : "❌ NON") . "\n";
        
        // URL générée
        $generatedUrl = media_url($media->chemin);
        echo "  URL: " . $generatedUrl . "\n";
        echo "  Type: " . $media->type_mime . "\n\n";
    }
}

// 3. Vérifier les relations
echo "🔗 VÉRIFICATION DES RELATIONS:\n";
$publications = Publication::with('medias')->whereHas('medias')->take(3)->get();
echo "Publications avec médias: " . $publications->count() . "\n\n";

foreach ($publications as $pub) {
    echo "Publication ID " . $pub->id . ":\n";
    echo "  Contenu: " . substr($pub->contenu, 0, 50) . "...\n";
    echo "  Médias: " . $pub->medias->count() . "\n";
    foreach ($pub->medias as $media) {
        echo "    - " . $media->nom_fichier . " (" . $media->type_mime . ")\n";
    }
    echo "\n";
}

echo "✅ TEST COMPLÉTÉ!\n\n";
echo "Les médias doivent maintenant s'afficher sur:\n";
echo "  - Feed: /feed\n";
echo "  - Groupes: /groupes/{id}\n\n";
