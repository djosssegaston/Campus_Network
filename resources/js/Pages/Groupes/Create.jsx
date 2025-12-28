import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';

export default function GroupeCreate() {
    const [nom, setNom] = useState('');
    const [description, setDescription] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        setError(null);
        setSuccess(false);

        if (!nom.trim()) {
            setError('Le nom du groupe est requis.');
            return;
        }

        setLoading(true);

        const formData = new FormData();
        formData.append('nom', nom);
        formData.append('description', description);

        window.axios.post('/api/v1/groupes', formData)
            .then((res) => {
                setSuccess(true);
                setNom('');
                setDescription('');
                setTimeout(() => {
                    window.location.href = `/groupes/${res.data.data?.id || res.data.id}`;
                }, 1500);
            })
            .catch((err) => {
                const msg = err.response?.data?.message || 'Erreur lors de la création du groupe.';
                setError(msg);
            })
            .finally(() => setLoading(false));
    };

    return (
        <AppLayout>
            <div className="max-w-2xl">
                <div className="flex items-center gap-2 mb-6">
                    <Link href="/groupes" className="text-blue-600 hover:text-blue-800">← Retour aux groupes</Link>
                </div>

                <h1 className="text-3xl font-bold text-gray-900 mb-6">Créer un groupe</h1>

                {error && (
                    <div className="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                        {error}
                    </div>
                )}

                {success && (
                    <div className="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                        Groupe créé avec succès! Redirection en cours...
                    </div>
                )}

                <form onSubmit={submit} className="bg-white rounded-lg shadow-sm p-6 space-y-4">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Nom du groupe
                        </label>
                        <input
                            type="text"
                            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ex: Informatique L1"
                            value={nom}
                            onChange={(e) => setNom(e.target.value)}
                            disabled={loading}
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea
                            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            rows="4"
                            placeholder="Décrivez le groupe..."
                            value={description}
                            onChange={(e) => setDescription(e.target.value)}
                            disabled={loading}
                        />
                        <p className="mt-2 text-sm text-gray-500">{description.length} caractères</p>
                    </div>

                    <div className="flex gap-3 pt-4">
                        <button
                            type="submit"
                            className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:bg-gray-400"
                            disabled={loading || !nom.trim()}
                        >
                            {loading ? 'Création en cours...' : 'Créer le groupe'}
                        </button>
                        <Link
                            href="/groupes"
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
