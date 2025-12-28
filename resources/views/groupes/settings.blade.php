@extends('layouts.app')

@section('title', 'Paramètres - ' . $groupe->nom)

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <a href="{{ route('groupes.show', $groupe->id) }}" class="text-blue-600 hover:text-blue-800 mb-6 inline-block">
        <i class="fas fa-arrow-left mr-1"></i>Retour au groupe
    </a>

    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-cog mr-2"></i>Paramètres du groupe
            </h1>
            <p class="text-gray-600">{{ $groupe->nom }}</p>
        </div>

        <!-- Messages d'alerte -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-red-800 mb-2">Erreurs de validation</h3>
                <ul class="list-disc list-inside text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 text-green-800">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <!-- Formulaire des paramètres -->
        <form action="{{ route('groupe-settings.update', $groupe->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section Informations générales -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Informations générales</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du groupe
                        </label>
                        <input 
                            type="text" 
                            id="nom" 
                            name="nom" 
                            value="{{ old('nom', $groupe->nom) }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="categorie" class="block text-sm font-medium text-gray-700 mb-2">
                            Catégorie
                        </label>
                        <input 
                            type="text" 
                            id="categorie" 
                            name="categorie" 
                            value="{{ old('categorie', $groupe->categorie) }}"
                            placeholder="Ex: Tech, Loisir, Sport..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4"
                        placeholder="Décrivez le but et le contenu du groupe..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                    >{{ old('description', $groupe->description) }}</textarea>
                </div>

                <div class="mt-6">
                    <label for="visibilite" class="block text-sm font-medium text-gray-700 mb-2">
                        Visibilité
                    </label>
                    <select 
                        id="visibilite" 
                        name="visibilite" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="public" @selected(old('visibilite', $groupe->visibilite) === 'public')>
                            🌐 Public - Visible par tous
                        </option>
                        <option value="prive" @selected(old('visibilite', $groupe->visibilite) === 'prive')>
                            🔒 Privé - Sur invitation
                        </option>
                        <option value="secret" @selected(old('visibilite', $groupe->visibilite) === 'secret')>
                            🔐 Secret - Invisible dans les recherches
                        </option>
                    </select>
                </div>
            </div>

            <!-- Section Permissions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Permissions et contrôle</h2>

                <div class="space-y-4">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="autoriser_messages" 
                            name="autoriser_messages" 
                            value="1"
                            @checked(old('autoriser_messages', $settings->autoriser_messages))
                            class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                        >
                        <label for="autoriser_messages" class="ml-3 text-gray-700">
                            <span class="font-medium">Autoriser les messages de groupe</span>
                            <p class="text-sm text-gray-500">Les membres peuvent envoyer des messages</p>
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="autoriser_publications" 
                            name="autoriser_publications" 
                            value="1"
                            @checked(old('autoriser_publications', $settings->autoriser_publications))
                            class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                        >
                        <label for="autoriser_publications" class="ml-3 text-gray-700">
                            <span class="font-medium">Autoriser les publications</span>
                            <p class="text-sm text-gray-500">Les membres peuvent publier du contenu</p>
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="autoriser_medias" 
                            name="autoriser_medias" 
                            value="1"
                            @checked(old('autoriser_medias', $settings->autoriser_medias))
                            class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                        >
                        <label for="autoriser_medias" class="ml-3 text-gray-700">
                            <span class="font-medium">Autoriser les médias</span>
                            <p class="text-sm text-gray-500">Images, vidéos, audio, fichiers</p>
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="moderation_requise" 
                            name="moderation_requise" 
                            value="1"
                            @checked(old('moderation_requise', $settings->moderation_requise))
                            class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                        >
                        <label for="moderation_requise" class="ml-3 text-gray-700">
                            <span class="font-medium">Modération requise</span>
                            <p class="text-sm text-gray-500">Les publications doivent être approuvées</p>
                        </label>
                    </div>
                </div>

                <!-- Permissions de publication -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label for="permission_publication" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-pencil mr-2"></i>Qui peut publier ?
                    </label>
                    <select 
                        id="permission_publication" 
                        name="permission_publication" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="tous" @selected(old('permission_publication', $settings->permission_publication) === 'tous')>
                            Tous les membres
                        </option>
                        <option value="moderateurs" @selected(old('permission_publication', $settings->permission_publication) === 'moderateurs')>
                            Modérateurs et admin
                        </option>
                        <option value="admin" @selected(old('permission_publication', $settings->permission_publication) === 'admin')>
                            Admin uniquement
                        </option>
                    </select>
                </div>

                <!-- Permissions de message -->
                <div class="mt-4">
                    <label for="permission_message" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comments mr-2"></i>Qui peut envoyer des messages ?
                    </label>
                    <select 
                        id="permission_message" 
                        name="permission_message" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="tous" @selected(old('permission_message', $settings->permission_message) === 'tous')>
                            Tous les membres
                        </option>
                        <option value="membres" @selected(old('permission_message', $settings->permission_message) === 'membres')>
                            Membres confirmés
                        </option>
                        <option value="admin" @selected(old('permission_message', $settings->permission_message) === 'admin')>
                            Admin uniquement
                        </option>
                    </select>
                </div>
            </div>

            <!-- Section Modération -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-shield-alt mr-2"></i>Modération
                </h2>

                <label for="mots_cles_interdits" class="block text-sm font-medium text-gray-700 mb-2">
                    Mots-clés interdits
                </label>
                <textarea 
                    id="mots_cles_interdits" 
                    name="mots_cles_interdits" 
                    rows="3"
                    placeholder="Entrez les mots à modérer, séparés par des virgules..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none font-mono text-sm"
                >{{ old('mots_cles_interdits', $settings->mots_cles_interdits ? implode(', ', $settings->mots_cles_interdits) : '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Les messages contenant ces mots seront signalés pour révision</p>
            </div>

            <!-- Boutons d'action -->
            <div class="flex gap-4">
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center gap-2"
                >
                    <i class="fas fa-save"></i>Enregistrer les modifications
                </button>

                <a 
                    href="{{ route('groupes.show', $groupe->id) }}" 
                    class="px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium flex items-center gap-2"
                >
                    <i class="fas fa-times"></i>Annuler
                </a>
            </div>
        </form>

        <!-- Section Suppression du groupe -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mt-6">
            <h2 class="text-xl font-bold text-red-800 mb-4">
                <i class="fas fa-exclamation-triangle mr-2"></i>Zone de danger
            </h2>
            
            <p class="text-red-700 mb-4">
                Supprimer ce groupe supprimera définitivement toutes les publications et messages. Cette action ne peut pas être annulée.
            </p>

            <button 
                onclick="if(confirm('Êtes-vous absolument sûr? Cette action est irréversible.')) { deleteGroup('{{ $groupe->id }}'); }"
                class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium"
            >
                <i class="fas fa-trash mr-2"></i>Supprimer le groupe
            </button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
function deleteGroup(groupeId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ route('groupe-settings.destroy', $groupe->id) }}`;
    form.innerHTML = `
        @csrf
        @method('DELETE')
    `;
    document.body.appendChild(form);
    form.submit();
    form.remove();
}
</script>

@endsection
