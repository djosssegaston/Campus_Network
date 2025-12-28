import React, { useEffect, useState } from 'react';
import { Link } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';

export default function GroupesList() {
    const [groupes, setGroupes] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [searchTerm, setSearchTerm] = useState('');

    useEffect(() => {
        setLoading(true);
        setError(null);

        window.axios.get('/api/v1/groupes')
            .then((res) => {
                const data = res.data.data ?? res.data;
                setGroupes(data || []);
            })
            .catch((err) => {
                setError('Erreur lors du chargement des groupes');
                console.error(err);
            })
            .finally(() => setLoading(false));
    }, []);

    const filtered = groupes.filter(g =>
        g.nom?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        g.description?.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <AppLayout>
            <div className="space-y-6">
                <div className="flex justify-between items-center">
                    <h1 className="text-3xl font-bold text-gray-900">Groupes</h1>
                    <Link
                        href="/groupes/create"
                        className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                    >
                        Créer un groupe
                    </Link>
                </div>

                {error && (
                    <div className="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                        {error}
                    </div>
                )}

                <div>
                    <input
                        type="text"
                        placeholder="Rechercher un groupe..."
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                {loading && (
                    <div className="text-center py-12">
                        <svg className="animate-spin h-8 w-8 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p className="mt-2 text-gray-600">Chargement des groupes...</p>
                    </div>
                )}

                {!loading && filtered.length === 0 && (
                    <div className="text-center py-12">
                        <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p className="mt-2 text-gray-600">Aucun groupe trouvé.</p>
                    </div>
                )}

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {filtered.map((groupe) => (
                        <Link
                            key={groupe.id}
                            href={`/groupes/${groupe.id}`}
                            className="bg-white rounded-lg shadow-sm hover:shadow-md transition p-6"
                        >
                            <div className="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg mb-4 flex items-center justify-center text-white font-bold text-xl">
                                {groupe.nom?.[0]?.toUpperCase() || 'G'}
                            </div>
                            <h3 className="font-semibold text-gray-900 text-lg mb-2">{groupe.nom}</h3>
                            <p className="text-sm text-gray-600 mb-4 line-clamp-2">{groupe.description}</p>
                            <div className="flex items-center justify-between">
                                <span className="text-xs text-gray-500">{groupe.utilisateurs_count || 0} membres</span>
                                <span className="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">{groupe.statut || 'Public'}</span>
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
