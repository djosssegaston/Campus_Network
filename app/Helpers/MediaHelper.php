<?php

/**
 * Retourne l'URL publique d'un média
 * Accède directement aux fichiers via la route /storage ou via le lien symbolique
 */
if (!function_exists('media_url')) {
    function media_url($path) {
        // Utilise la route /storage/{path} créée dans web.php
        // Si le lien symbolique existe, utilise asset() directement
        if (is_link(public_path('storage'))) {
            return asset('storage/' . $path);
        }
        // Sinon, utilise la route de servage
        return '/storage/' . $path;
    }
}
