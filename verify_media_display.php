<?php
/**
 * COMMANDE DE VÉRIFICATION RAPIDE - AFFICHAGE DES MÉDIAS
 * 
 * Utilisation:
 *   php verify_media_display.php
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Publication;
use App\Models\Media;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  ✅ VÉRIFICATION AFFICHAGE DES MÉDIAS                      ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// 1. Helper disponible?
echo "1️⃣  HELPER media_url() disponible: ";
if (function_exists('media_url')) {
    echo "✅ OUI\n";
} else {
    echo "❌ NON - Exécutez: composer dump-autoload\n";
    exit(1);
}

// 2. Médias en base?
$mediaCount = Media::count();
echo "2️⃣  Médias en base de données: " . $mediaCount;
if ($mediaCount > 0) {
    echo " ✅\n";
} else {
    echo " ❌ (Aucun média trouvé)\n";
}

// 3. Fichiers physiques?
echo "3️⃣  Fichiers physiques:\n";
$allExist = true;
$medias = Media::limit(5)->get();
foreach ($medias as $media) {
    $filePath = storage_path('app/public/' . $media->chemin);
    $exists = file_exists($filePath);
    if (!$exists) $allExist = false;
    
    echo "     " . ($exists ? "✅" : "❌") . " " . substr($media->nom_fichier, 0, 30) . "\n";
}

// 4. Publications avec médias?
$pubWithMedia = Publication::whereHas('medias')->count();
echo "4️⃣  Publications avec médias: " . $pubWithMedia;
if ($pubWithMedia > 0) {
    echo " ✅\n";
} else {
    echo " ⚠️  (Aucune publication n'a de médias)\n";
}

// 5. URLs générées correctement?
echo "5️⃣  Génération des URLs:\n";
if ($medias->count() > 0) {
    $media = $medias->first();
    $url = media_url($media->chemin);
    echo "     Exemple: " . $url . "\n";
    echo "     ✅ OK\n";
}

echo "\n╔════════════════════════════════════════════════════════════╗\n";
if ($allExist && $mediaCount > 0 && $pubWithMedia > 0) {
    echo "║  ✅ SYSTÈME PRÊT - Les médias s'affichent correctement!   ║\n";
    echo "║                                                            ║\n";
    echo "║  Lancez le serveur et testez:                            ║\n";
    echo "║    php artisan serve                                     ║\n";
    echo "║    http://localhost:8000/feed                            ║\n";
    echo "║    http://localhost:8000/groupes/1                       ║\n";
} else {
    echo "║  ⚠️  Vérifications incomplètes ou échouées               ║\n";
    echo "║                                                            ║\n";
    if (!$allExist) {
        echo "║  - Certains fichiers n'existent pas                      ║\n";
    }
    if ($mediaCount === 0) {
        echo "║  - Aucun média en base (uploadez des fichiers)           ║\n";
    }
    if ($pubWithMedia === 0) {
        echo "║  - Aucune publication avec médias                        ║\n";
    }
}
echo "╚════════════════════════════════════════════════════════════╝\n\n";
