@extends('layouts.app')

@section('title', 'Paramètres de Confidentialité')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour au profil
            </a>
            <h1 class="text-4xl font-bold text-gray-900">Paramètres de Confidentialité</h1>
            <p class="text-gray-600 mt-2">Contrôlez qui peut voir vos informations et vos activités</p>
        </div>

        <!-- Messages de statut -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="font-semibold text-red-800 mb-2">Erreurs de validation</h3>
                <ul class="list-disc list-inside text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-green-800">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </p>
            </div>
        @endif

        <!-- Formulaire -->
        <form method="POST" action="{{ route('privacy-settings.update') }}" class="space-y-8">
            @csrf
            @method('PATCH')

            <!-- Section : Visibilité du Profil -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user-shield mr-2 text-blue-600"></i>
                    Visibilité du Profil
                </h2>
                
                <div class="space-y-3">
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="radio" 
                            name="profil_visibilite" 
                            value="public"
                            {{ $privacySettings->profil_visibilite === 'public' ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600"
                        >
                        <span class="ml-3">
                            <span class="font-semibold text-gray-900">Public</span>
                            <p class="text-gray-600 text-sm">Tout le monde peut voir votre profil</p>
                        </span>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="radio" 
                            name="profil_visibilite" 
                            value="amis"
                            {{ $privacySettings->profil_visibilite === 'amis' ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600"
                        >
                        <span class="ml-3">
                            <span class="font-semibold text-gray-900">Amis seulement</span>
                            <p class="text-gray-600 text-sm">Seuls vos amis/contacts peuvent voir votre profil</p>
                        </span>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="radio" 
                            name="profil_visibilite" 
                            value="prive"
                            {{ $privacySettings->profil_visibilite === 'prive' ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600"
                        >
                        <span class="ml-3">
                            <span class="font-semibold text-gray-900">Privé</span>
                            <p class="text-gray-600 text-sm">Seul vous pouvez voir votre profil</p>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Section : Communications -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-comment-dots mr-2 text-green-600"></i>
                    Communications
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block font-semibold text-gray-900 mb-2">Qui peut m'envoyer des messages ?</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="messages_acceptes" 
                                    value="tous"
                                    {{ $privacySettings->messages_acceptes === 'tous' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600"
                                >
                                <span class="ml-2 text-gray-700">Tout le monde</span>
                            </label>
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="messages_acceptes" 
                                    value="amis"
                                    {{ $privacySettings->messages_acceptes === 'amis' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600"
                                >
                                <span class="ml-2 text-gray-700">Amis seulement</span>
                            </label>
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="messages_acceptes" 
                                    value="personne"
                                    {{ $privacySettings->messages_acceptes === 'personne' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600"
                                >
                                <span class="ml-2 text-gray-700">Personne</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-900 mb-2">Qui peut voir mes publications ?</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="publications_lisibles" 
                                    value="public"
                                    {{ $privacySettings->publications_lisibles === 'public' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600"
                                >
                                <span class="ml-2 text-gray-700">Tout le monde</span>
                            </label>
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="publications_lisibles" 
                                    value="amis"
                                    {{ $privacySettings->publications_lisibles === 'amis' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600"
                                >
                                <span class="ml-2 text-gray-700">Amis seulement</span>
                            </label>
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="publications_lisibles" 
                                    value="prive"
                                    {{ $privacySettings->publications_lisibles === 'prive' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600"
                                >
                                <span class="ml-2 text-gray-700">Privé (personne ne peut les voir)</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-900 mb-2">Qui peut commenter mes publications ?</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="commentaires_acceptes" 
                                    value="tous"
                                    {{ $privacySettings->commentaires_acceptes === 'tous' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600"
                                >
                                <span class="ml-2 text-gray-700">Tout le monde</span>
                            </label>
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="commentaires_acceptes" 
                                    value="amis"
                                    {{ $privacySettings->commentaires_acceptes === 'amis' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600"
                                >
                                <span class="ml-2 text-gray-700">Amis seulement</span>
                            </label>
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="commentaires_acceptes" 
                                    value="personne"
                                    {{ $privacySettings->commentaires_acceptes === 'personne' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600"
                                >
                                <span class="ml-2 text-gray-700">Personne</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Visibilité des Informations -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-eye mr-2 text-purple-600"></i>
                    Visibilité des Informations
                </h2>
                
                <div class="space-y-3">
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="afficher_contacts"
                            {{ $privacySettings->afficher_contacts ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded"
                        >
                        <span class="ml-3">
                            <span class="font-semibold text-gray-900">Afficher ma liste de contacts</span>
                            <p class="text-gray-600 text-sm">Les autres peuvent voir mes contacts/amis</p>
                        </span>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="afficher_groupes"
                            {{ $privacySettings->afficher_groupes ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded"
                        >
                        <span class="ml-3">
                            <span class="font-semibold text-gray-900">Afficher mes groupes</span>
                            <p class="text-gray-600 text-sm">Les autres peuvent voir les groupes auxquels j'appartiens</p>
                        </span>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="afficher_activite"
                            {{ $privacySettings->afficher_activite ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded"
                        >
                        <span class="ml-3">
                            <span class="font-semibold text-gray-900">Afficher mon historique d'activité</span>
                            <p class="text-gray-600 text-sm">Montrer quand j'ai été actif en dernier</p>
                        </span>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="mentions_autorisees"
                            {{ $privacySettings->mentions_autorisees ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded"
                        >
                        <span class="ml-3">
                            <span class="font-semibold text-gray-900">Autoriser les mentions</span>
                            <p class="text-gray-600 text-sm">Les autres peuvent me mentionner dans des publications</p>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Section : Notifications -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bell mr-2 text-orange-600"></i>
                    Préférences de Notifications
                </h2>
                
                <div class="space-y-3">
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="notifier_requetes_contact"
                            {{ $privacySettings->notifier_requetes_contact ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded"
                        >
                        <span class="ml-3">
                            <span class="font-semibold text-gray-900">Nouvelles demandes de contact</span>
                            <p class="text-gray-600 text-sm">Me notifier quand quelqu'un me demande en ami</p>
                        </span>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="notifier_commentaires"
                            {{ $privacySettings->notifier_commentaires ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded"
                        >
                        <span class="ml-3">
                            <span class="font-semibold text-gray-900">Nouveaux commentaires</span>
                            <p class="text-gray-600 text-sm">Me notifier quand quelqu'un commente mes publications</p>
                        </span>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="notifier_reactions"
                            {{ $privacySettings->notifier_reactions ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded"
                        >
                        <span class="ml-3">
                            <span class="font-semibold text-gray-900">Réactions sur mes publications</span>
                            <p class="text-gray-600 text-sm">Me notifier quand quelqu'un aime mes publications</p>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Section : Groupes -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-layer-group mr-2 text-indigo-600"></i>
                    Visibilité dans les Groupes
                </h2>
                
                <div class="space-y-3">
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="radio" 
                            name="groupes_visibles" 
                            value="public"
                            {{ $privacySettings->groupes_visibles === 'public' ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600"
                        >
                        <span class="ml-3">
                            <span class="font-semibold text-gray-900">Public</span>
                            <p class="text-gray-600 text-sm">Tout le monde voit que je suis dans ce groupe</p>
                        </span>
                    </label>

                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input 
                            type="radio" 
                            name="groupes_visibles" 
                            value="prive"
                            {{ $privacySettings->groupes_visibles === 'prive' ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600"
                        >
                        <span class="ml-3">
                            <span class="font-semibold text-gray-900">Privé</span>
                            <p class="text-gray-600 text-sm">Seuls les membres du groupe voient que j'en fais partie</p>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex gap-4">
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition flex items-center"
                >
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer les modifications
                </button>
                <a 
                    href="{{ route('profile.edit') }}" 
                    class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition flex items-center"
                >
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
