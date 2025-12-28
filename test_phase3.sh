#!/bin/bash

# 🚀 QUICK TEST - Campus Network Social Features
# Test rapide des 7 fonctionnalités critiques

echo "🔍 CAMPUS NETWORK - QUICK TEST SUITE"
echo "======================================"
echo ""

# Test 1: Vérifier les fichiers cruciaux existent
echo "📋 TEST 1: Vérification fichiers"
echo "---------------------------------"

REQUIRED_FILES=(
    "app/Http/Controllers/PublicationController.php"
    "app/Http/Controllers/Api/PublicationController.php"
    "app/Http/Controllers/FeedController.php"
    "app/Models/Publication.php"
    "app/Models/Groupe.php"
    "app/Models/Message.php"
    "app/Models/Commentaire.php"
    "app/Models/Reaction.php"
    "resources/views/publications/create.blade.php"
    "resources/views/feed.blade.php"
    "routes/web.php"
    "routes/api.php"
)

SUCCESS=0
FAILED=0

for file in "${REQUIRED_FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file"
        ((SUCCESS++))
    else
        echo "❌ $file"
        ((FAILED++))
    fi
done

echo ""
echo "Résultat: $SUCCESS/12 fichiers trouvés"
echo ""

# Test 2: Vérifier routes
echo "📍 TEST 2: Vérification routes"
echo "------------------------------"

echo "Routes Web publication:"
grep -n "Route::.*publication" routes/web.php | head -5 || echo "❌ Routes not found"

echo ""
echo "Routes API publication:"
grep -n "Route::.*publication" routes/api.php | head -5 || echo "❌ Routes not found"

echo ""

# Test 3: Vérifier syntaxe PHP
echo "🔧 TEST 3: Vérification syntaxe PHP"
echo "-----------------------------------"

echo "PublicationController Web:"
php -l app/Http/Controllers/PublicationController.php 2>&1 | grep -i "success\|error"

echo "PublicationController API:"
php -l app/Http/Controllers/Api/PublicationController.php 2>&1 | grep -i "success\|error"

echo "FeedController:"
php -l app/Http/Controllers/FeedController.php 2>&1 | grep -i "success\|error"

echo ""

# Test 4: Vérifier modèles
echo "📦 TEST 4: Vérification modèles"
echo "-------------------------------"

MODELS=(
    "Publication"
    "Groupe"
    "Message"
    "Conversation"
    "Commentaire"
    "Reaction"
)

for model in "${MODELS[@]}"; do
    if grep -q "class $model" app/Models/${model}.php 2>/dev/null; then
        echo "✅ Model $model found"
    else
        echo "❌ Model $model NOT found"
    fi
done

echo ""
echo "📊 TEST SUMMARY"
echo "==============="
echo "✅ Files: $SUCCESS/12"
echo "❌ Missing: $FAILED"
echo ""
echo "🟢 Ready to test in browser:"
echo "   1. GET /publications/create (load form)"
echo "   2. POST /publications (submit form)"
echo "   3. GET /feed (see publication)"
echo "   4. POST /api/v1/publications/{id}/commentaires (comment)"
echo "   5. POST /api/v1/publications/{id}/reactions (like)"
