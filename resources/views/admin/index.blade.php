@extends('layouts.app')

@section('title', 'Administration')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    @php
        $user = auth()->user();
        $roleSlug = optional($user->role)->slug ?? 'guest';
    @endphp

    @if(in_array($roleSlug, ['administrateur', 'super_admin']))
        <!-- Header Admin -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">
                @if($roleSlug === 'super_admin')
                    🔐 Super Administrateur
                @else
                    ⚡ Administrateur
                @endif
            </h1>
            <p class="text-gray-600 mt-2">Panneau de contrôle complet du système</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500">
                <p class="text-gray-500 text-sm font-semibold uppercase">Utilisateurs</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">1,247</p>
                <p class="text-green-600 text-sm mt-2">↑ 125 cette semaine</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-500">
                <p class="text-gray-500 text-sm font-semibold uppercase">Groupes Actifs</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">89</p>
                <p class="text-green-600 text-sm mt-2">↑ 5 cette semaine</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-500">
                <p class="text-gray-500 text-sm font-semibold uppercase">Publications</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">3.5K</p>
                <p class="text-green-600 text-sm mt-2">↑ 342 cette semaine</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-orange-500">
                <p class="text-gray-500 text-sm font-semibold uppercase">Rapports</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">12</p>
                <p class="text-orange-600 text-sm mt-2">En attente</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-red-500">
                <p class="text-gray-500 text-sm font-semibold uppercase">Bannissements</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">8</p>
                <p class="text-red-600 text-sm mt-2">Utilisateurs actifs</p>
            </div>
        </div>

        <!-- Management Sections -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- User Management -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-users text-blue-600 mr-2"></i>Gestion Utilisateurs
                </h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition border border-blue-200">
                        <i class="fas fa-user-plus text-blue-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Créer Utilisateur</p>
                            <p class="text-sm text-gray-600">Ajouter un nouvel utilisateur</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition border border-orange-200">
                        <i class="fas fa-edit text-orange-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Éditer Utilisateurs</p>
                            <p class="text-sm text-gray-600">Modifier les données utilisateur</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition border border-red-200">
                        <i class="fas fa-ban text-red-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Bannir Utilisateurs</p>
                            <p class="text-sm text-gray-600">Bannir/débannir des utilisateurs</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Roles & Permissions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-shield-alt text-green-600 mr-2"></i>Rôles & Permissions
                </h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition border border-green-200">
                        <i class="fas fa-list text-green-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Voir Rôles</p>
                            <p class="text-sm text-gray-600">Lister tous les rôles existants</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition border border-blue-200">
                        <i class="fas fa-plus-circle text-blue-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Créer Rôle</p>
                            <p class="text-sm text-gray-600">Ajouter un nouveau rôle</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition border border-purple-200">
                        <i class="fas fa-cog text-purple-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Gérer Permissions</p>
                            <p class="text-sm text-gray-600">Assigner permissions aux rôles</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Management -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- Content Moderation -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-eye text-orange-600 mr-2"></i>Modération Contenu
                </h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition border border-orange-200">
                        <i class="fas fa-exclamation-circle text-orange-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Rapports Signalés</p>
                            <p class="text-sm text-gray-600">12 rapports en attente</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition border border-red-200">
                        <i class="fas fa-trash text-red-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Supprimer Contenus</p>
                            <p class="text-sm text-gray-600">Gérer les publications</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition border border-yellow-200">
                        <i class="fas fa-history text-yellow-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Historique Modération</p>
                            <p class="text-sm text-gray-600">Actions de modération récentes</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Analytics & Reporting -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-chart-line text-cyan-600 mr-2"></i>Analytics
                </h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center p-4 bg-cyan-50 rounded-lg hover:bg-cyan-100 transition border border-cyan-200">
                        <i class="fas fa-chart-bar text-cyan-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Statistiques Complètes</p>
                            <p class="text-sm text-gray-600">Vue d'ensemble du système</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition border border-indigo-200">
                        <i class="fas fa-users text-indigo-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Comportement Utilisateurs</p>
                            <p class="text-sm text-gray-600">Analyse d'engagement</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-4 bg-lime-50 rounded-lg hover:bg-lime-100 transition border border-lime-200">
                        <i class="fas fa-download text-lime-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Exporter Rapports</p>
                            <p class="text-sm text-gray-600">Télécharger les données</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Management -->
        <div class="grid grid-cols-2 gap-8">
            <!-- Settings -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-sliders-h text-purple-600 mr-2"></i>Paramètres Système
                </h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition border border-purple-200">
                        <i class="fas fa-cog text-purple-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Configuration Globale</p>
                            <p class="text-sm text-gray-600">Paramètres de la plateforme</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition border border-pink-200">
                        <i class="fas fa-bell text-pink-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Notifications</p>
                            <p class="text-sm text-gray-600">Gérer les emails</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-4 bg-cyan-50 rounded-lg hover:bg-cyan-100 transition border border-cyan-200">
                        <i class="fas fa-lock text-cyan-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Sécurité</p>
                            <p class="text-sm text-gray-600">Paramètres de sécurité</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Maintenance -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-hammer text-gray-600 mr-2"></i>Maintenance
                </h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition border border-gray-200">
                        <i class="fas fa-database text-gray-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Gestion BD</p>
                            <p class="text-sm text-gray-600">Sauvegarder/Restaurer</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition border border-blue-200">
                        <i class="fas fa-file-alt text-blue-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Logs Système</p>
                            <p class="text-sm text-gray-600">Voir les événements</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition border border-green-200">
                        <i class="fas fa-heartbeat text-green-600 text-2xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-900">État du Système</p>
                            <p class="text-sm text-gray-600">Santé de la plateforme</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-lock text-6xl text-red-400 mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Accès Refusé</h2>
            <p class="text-gray-600">
                Vous n'avez pas les permissions nécessaires pour accéder au panneau d'administration.
            </p>
            <a href="{{ route('dashboard') }}" class="inline-block mt-6 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour au Dashboard
            </a>
        </div>
    @endif
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
