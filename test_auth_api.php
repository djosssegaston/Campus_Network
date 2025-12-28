#!/usr/bin/env php
<?php

/**
 * Script de test API Campus Network
 * Teste les endpoints d'authentification
 */

$baseUrl = 'http://localhost:8000/api/v1';
$email = 'testauth_' . time() . '@example.com';
$password = 'TestPassword123!';

echo "═════════════════════════════════════════════════════\n";
echo "  TEST API AUTHENTICATION - Campus Network\n";
echo "═════════════════════════════════════════════════════\n\n";

// Test 1: Enregistrement
echo "📝 TEST 1: Enregistrement\n";
echo "────────────────────────────────────────────────────\n";

$registerData = [
    'nom' => 'Test User ' . time(),
    'email' => $email,
    'password' => $password,
    'password_confirmation' => $password,
    'filiere' => 'Informatique',
    'annee_etude' => 2
];

$response = makeRequest('POST', "$baseUrl/auth/register", $registerData);

if ($response['status'] == 201) {
    echo "✅ Enregistrement réussi\n";
    $token = $response['body']['token'] ?? null;
    $userId = $response['body']['user']['id'] ?? null;
    echo "   Token: " . substr($token, 0, 20) . "...\n";
    echo "   User ID: $userId\n";
    echo "   Email: " . $response['body']['user']['email'] . "\n\n";
} else {
    echo "❌ Enregistrement échoué\n";
    echo "   Status: " . $response['status'] . "\n";
    echo "   Message: " . ($response['body']['message'] ?? 'Unknown') . "\n\n";
    exit(1);
}

// Test 2: Connexion
echo "🔐 TEST 2: Connexion\n";
echo "────────────────────────────────────────────────────\n";

$loginData = [
    'email' => $email,
    'password' => $password
];

$response = makeRequest('POST', "$baseUrl/auth/login", $loginData);

if ($response['status'] == 200) {
    echo "✅ Connexion réussie\n";
    $token = $response['body']['token'] ?? null;
    echo "   Token: " . substr($token, 0, 20) . "...\n";
    echo "   User: " . $response['body']['user']['nom'] . "\n\n";
} else {
    echo "❌ Connexion échouée\n";
    echo "   Status: " . $response['status'] . "\n";
    echo "   Message: " . ($response['body']['message'] ?? 'Unknown') . "\n\n";
    exit(1);
}

// Test 3: Profil utilisateur
echo "👤 TEST 3: Profil utilisateur\n";
echo "────────────────────────────────────────────────────\n";

$response = makeRequest('GET', "$baseUrl/auth/me", null, $token);

if ($response['status'] == 200) {
    echo "✅ Profil récupéré\n";
    echo "   Nom: " . $response['body']['user']['nom'] . "\n";
    echo "   Email: " . $response['body']['user']['email'] . "\n";
    echo "   Filière: " . ($response['body']['user']['filiere'] ?? 'N/A') . "\n\n";
} else {
    echo "❌ Échec récupération profil\n";
    echo "   Status: " . $response['status'] . "\n\n";
}

// Test 4: Connexion avec mauvais mot de passe
echo "❌ TEST 4: Connexion avec mauvais mot de passe\n";
echo "────────────────────────────────────────────────────\n";

$badLoginData = [
    'email' => $email,
    'password' => 'WrongPassword123!'
];

$response = makeRequest('POST', "$baseUrl/auth/login", $badLoginData);

if ($response['status'] == 401) {
    echo "✅ Rejet correctement effectué\n";
    echo "   Message: " . $response['body']['message'] . "\n\n";
} else {
    echo "❌ Erreur: devrait retourner 401\n\n";
}

// Test 5: Déconnexion
echo "🚪 TEST 5: Déconnexion\n";
echo "────────────────────────────────────────────────────\n";

$response = makeRequest('POST', "$baseUrl/auth/logout", null, $token);

if ($response['status'] == 200) {
    echo "✅ Déconnexion réussie\n";
    echo "   Message: " . $response['body']['message'] . "\n\n";
} else {
    echo "❌ Déconnexion échouée\n\n";
}

echo "═════════════════════════════════════════════════════\n";
echo "  ✅ TOUS LES TESTS COMPLÉTÉS\n";
echo "═════════════════════════════════════════════════════\n";

/**
 * Fonction helper pour faire les requêtes HTTP
 */
function makeRequest($method, $url, $data = null, $token = null)
{
    $ch = curl_init($url);
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
    ];
    
    if ($token) {
        $headers[] = "Authorization: Bearer $token";
    }
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status' => $httpCode,
        'body' => json_decode($response, true)
    ];
}
