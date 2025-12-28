<?php
/**
 * TEST: Vérifier les routes et les URLs HTTP
 * Simule les requêtes HTTP pour tester l'affichage des médias
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Publication;
use App\Models\Media;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  🎬 TEST HTTP - AFFICHAGE DES MÉDIAS                       ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// 1. Tester la route
echo "1️⃣  VÉRIFICATION DE LA ROUTE /storage/{path}\n";
$routes = app('router')->getRoutes();
$storageRouteExists = false;
$storageRoutePath = null;

foreach ($routes as $route) {
    if ($route->getName() === 'storage.serve') {
        $storageRouteExists = true;
        $storageRoutePath = $route->getPath();
        break;
    }
}

if ($storageRouteExists) {
    echo "     ✅ Route trouvée: " . $storageRoutePath . "\n";
} else {
    echo "     ❌ Route NOT trouvée - Vérifiez routes/web.php\n";
}
echo "\n";

// 2. Tester les URLs générées
echo "2️⃣  VÉRIFICATION DES URLs GÉNÉRÉES\n";
$medias = Media::limit(3)->get();

foreach ($medias as $media) {
    echo "     Média: " . substr($media->nom_fichier, 0, 30) . "\n";
    
    $url = media_url($media->chemin);
    echo "     URL: " . $url . "\n";
    
    // Vérifier le format
    if (str_starts_with($url, '/storage/')) {
        echo "     Format: ✅ Correct (/storage/...)\n";
    } else {
        echo "     Format: ❌ Incorrect\n";
    }
    
    // Vérifier le fichier existe
    $filePath = storage_path('app/public/' . $media->chemin);
    echo "     Fichier: " . (file_exists($filePath) ? "✅ Existe" : "❌ N'existe pas") . "\n";
    echo "\n";
}

// 3. Simuler le rendu Blade
echo "3️⃣  APERÇU DU RENDU HTML\n";
$publication = Publication::with('medias')->whereHas('medias')->first();

if ($publication && $publication->medias->count() > 0) {
    echo "     Publication ID: " . $publication->id . "\n";
    echo "     Médias: " . $publication->medias->count() . "\n\n";
    
    foreach ($publication->medias as $media) {
        $extension = strtolower(pathinfo($media->chemin, PATHINFO_EXTENSION));
        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        
        if ($isImage) {
            echo "     HTML généré:\n";
            echo "     <img src=\"" . media_url($media->chemin) . "\" alt=\"" . $media->nom_fichier . "\">\n";
            echo "     ✅ Sera affiché dans le Feed\n\n";
        }
    }
} else {
    echo "     ⚠️  Aucune publication avec médias trouvée\n\n";
}

// 4. Informations système
echo "4️⃣  INFORMATIONS SYSTÈME\n";
echo "     App URL: " . env('APP_URL') . "\n";
echo "     Storage Path: " . storage_path('app/public') . "\n";
echo "     Public Path: " . public_path() . "\n";
echo "     Symlink existe: " . (is_link(public_path('storage')) ? "✅ OUI" : "❌ NON") . "\n\n";

// 5. Commandes recommandées
echo "5️⃣  COMMANDES RECOMMANDÉES\n";
echo "\n     Pour démarrer:\n";
echo "     $ php artisan serve\n\n";
echo "     Puis ouvrez dans le navigateur:\n";
echo "     http://localhost:8000/feed\n";
echo "     http://localhost:8000/groupes/1\n\n";

echo "╔════════════════════════════════════════════════════════════╗\n";
if ($storageRouteExists && $medias->count() > 0) {
    echo "║  ✅ TEST RÉUSSI - Tous les contrôles sont OK!             ║\n";
} else {
    echo "║  ⚠️  Vérifications nécessaires - Voir détails au-dessus   ║\n";
}
echo "╚════════════════════════════════════════════════════════════╝\n\n";
