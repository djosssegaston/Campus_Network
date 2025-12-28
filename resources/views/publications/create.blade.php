@extends('layouts.app')

@section('title', 'Créer une Publication')

@section('content')
<div class="p-8 max-w-2xl">
    <h2 class="text-3xl font-bold text-gray-900 mb-6">Créer une Publication</h2>

    <div class="bg-white rounded-lg shadow p-6">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="font-semibold text-red-800 mb-2">Erreurs</h3>
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('publications.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label for="contenu" class="block text-sm font-medium text-gray-700 mb-2">Contenu</label>
                <textarea id="contenu" name="contenu" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contenu') border-red-500 @enderror" placeholder="Écrivez votre publication..." required>{{ old('contenu') }}</textarea>
                @error('contenu')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="visibilite" class="block text-sm font-medium text-gray-700 mb-2">Visibilité</label>
                <select id="visibilite" name="visibilite" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('visibilite') border-red-500 @enderror">
                    <option value="public" {{ old('visibilite') == 'public' ? 'selected' : '' }}>🌍 Publique</option>
                    <option value="amis" {{ old('visibilite') == 'amis' ? 'selected' : '' }}>👥 Amis seulement</option>
                    <option value="prive" {{ old('visibilite') == 'prive' ? 'selected' : '' }}>🔒 Privé</option>
                </select>
                @error('visibilite')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 📤 UPLOAD DE FICHIERS -->
            <div class="mb-6">
                <label for="medias" class="block text-sm font-medium text-gray-700 mb-2">📸 Images, Vidéos, Sons</label>
                <div class="border-2 border-dashed border-blue-300 rounded-lg p-6 bg-blue-50 cursor-pointer hover:border-blue-500 transition" id="dropzone">
                    <input type="file" id="medias" name="medias[]" multiple accept="image/*,video/*,audio/*" class="hidden" onchange="handleFileSelect(event)">
                    
                    <div class="text-center" id="dropzone-text">
                        <svg class="mx-auto h-12 w-12 text-blue-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            <circle cx="14" cy="20" r="4" stroke-width="2"></circle>
                            <path d="M40 12L21 31" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-700">Cliquez pour ajouter des fichiers</p>
                        <p class="mt-1 text-xs text-gray-500">ou glissez-déposez</p>
                    </div>
                </div>
                
                <!-- Liste des fichiers sélectionnés -->
                <div id="file-list" class="mt-4 space-y-2"></div>
                
                @error('medias')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    ✨ Publier
                </button>
                <a href="{{ route('feed.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-medium">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Gestion du drag-drop et file upload
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('medias');
const fileList = document.getElementById('file-list');
let selectedFiles = [];

// Click sur la zone
dropzone.addEventListener('click', () => fileInput.click());

// Drag-drop
dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('border-blue-500', 'bg-blue-100');
});

dropzone.addEventListener('dragleave', () => {
    dropzone.classList.remove('border-blue-500', 'bg-blue-100');
});

dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('border-blue-500', 'bg-blue-100');
    handleFiles(e.dataTransfer.files);
});

// Gestion des fichiers sélectionnés
function handleFileSelect(event) {
    handleFiles(event.target.files);
}

function handleFiles(files) {
    const maxSize = 100 * 1024 * 1024; // 100 MB
    
    for (let file of files) {
        // Valide la taille
        if (file.size > maxSize) {
            alert(`${file.name} dépasse 100 MB`);
            continue;
        }
        
        // Valide le type
        if (!file.type.match(/image\/*|video\/*|audio\/*.*/)) {
            alert(`${file.name} n'est pas un type supporté`);
            continue;
        }
        
        // Ajoute à la liste (si pas déjà présent)
        if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
            selectedFiles.push(file);
        }
    }
    
    updateFileList();
    updateFileInput();
}

function updateFileList() {
    fileList.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        const type = file.type.startsWith('image/') ? '🖼️' : 
                     file.type.startsWith('video/') ? '🎬' : '🎵';
        
        const size = (file.size / 1024 / 1024).toFixed(2);
        
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between p-3 bg-gray-100 rounded-lg';
        div.innerHTML = `
            <div class="flex items-center gap-2">
                <span>${type}</span>
                <div>
                    <p class="text-sm font-medium text-gray-900">${file.name}</p>
                    <p class="text-xs text-gray-500">${size} MB</p>
                </div>
            </div>
            <button type="button" onclick="removeFile(${index})" class="text-red-600 hover:text-red-800">
                ✕ Supprimer
            </button>
        `;
        fileList.appendChild(div);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    updateFileList();
    updateFileInput();
}

function updateFileInput() {
    // Crée une DataTransfer pour mettre à jour l'input
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    fileInput.files = dataTransfer.files;
}
</script>
@endsection
