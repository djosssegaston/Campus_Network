import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import AppLayout from '../Layouts/AppLayout';

export default function PublicationCreate() {
    const [contenu, setContenu] = useState('');
    const [visibilite, setVisibilite] = useState('publique');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        setError(null);
        setSuccess(false);

        if (!contenu.trim()) {
            setError('Le contenu de la publication ne peut pas être vide.');
            return;
        }

        setLoading(true);

        const formData = new FormData();
        formData.append('contenu', contenu);
        formData.append('visibilite', visibilite);

        window.axios.post('/api/v1/publications', formData)
            .then(() => {
                setSuccess(true);
                setContenu('');
                setVisibilite('publique');
                setTimeout(() => {
                    window.location.href = '/feed';
                }, 1500);
            })
            .catch((err) => {
                const msg = err.response?.data?.message || 'Erreur lors de la création de la publication.';
                setError(msg);
            })
            .finally(() => setLoading(false));
    };

    return (
        <AppLayout>
            <div className="max-w-2xl">
                <div className="flex items-center gap-2 mb-6">
                    <Link href="/feed" className="text-blue-600 hover:text-blue-800">← Retour au fil</Link>
                </div>

                <h1 className="text-3xl font-bold text-gray-900 mb-6">Créer une publication</h1>

                {error && (
                    <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                        {error}
                    </div>
                )}

                {success && (
                    <div className="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                        Publication créée avec succès! Redirection en cours...
                    </div>
                )}

                <form onSubmit={submit} className="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Contenu
                        </label>
                        <textarea
                            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            rows="6"
                            placeholder="Exprimez-vous... Partagez vos pensées avec la communauté."
                            value={contenu}
                            onChange={(e) => setContenu(e.target.value)}
                            disabled={loading}
                        />
                        <p className="mt-2 text-sm text-gray-500">{contenu.length} caractères</p>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Visibilité
                        </label>
                        <select
                            value={visibilite}
                            onChange={(e) => setVisibilite(e.target.value)}
                            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            disabled={loading}
                        >
                            <option value="publique">Publique</option>
                            <option value="prive">Privé</option>
                        </select>
                        <p className="mt-1 text-xs text-gray-500">
                            {visibilite === 'publique' ? '✓ Visible par tous' : '✓ Visible uniquement par vous'}
                        </p>
                    </div>

                    <div className="flex gap-3 pt-4">
                        <button
                            type="submit"
                            className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:bg-gray-400"
                            disabled={loading || !contenu.trim()}
                        >
                            {loading ? 'Publication en cours...' : 'Publier'}
                        </button>
                        <Link
                            href="/feed"
                            className="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
                        >
                            Annuler
                        </Link>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
