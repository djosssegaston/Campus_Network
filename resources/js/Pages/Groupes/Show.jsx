import React, { useEffect, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';

export default function GroupeShow({ groupeId }) {
    const { auth } = usePage().props;
    const [groupe, setGroupe] = useState(null);
    const [membres, setMembres] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [isMember, setIsMember] = useState(false);
    const [isJoining, setIsJoining] = useState(false);

    const id = groupeId || window.location.pathname.split('/').pop();

    useEffect(() => {
        setLoading(true);
        setError(null);

        window.axios.get(`/api/v1/groupes/${id}`)
            .then((res) => {
                const data = res.data.data ?? res.data;
                setGroupe(data);
                setMembres(data.utilisateurs || []);
                setIsMember(data.utilisateurs?.some(u => u.id === auth?.user?.id) || false);
            })
            .catch((err) => {
                setError('Erreur lors du chargement du groupe');
                console.error(err);
            })
            .finally(() => setLoading(false));
    }, [id]);

    const handleJoin = () => {
        setIsJoining(true);
        window.axios.post(`/api/v1/groupes/${id}/join`)
            .then(() => {
                setIsMember(true);
                // Reload groupe
                return window.axios.get(`/api/v1/groupes/${id}`);
            })
            .then((res) => {
                const data = res.data.data ?? res.data;
                setMembres(data.utilisateurs || []);
            })
            .catch((err) => {
                setError('Erreur lors de la connexion au groupe');
            })
            .finally(() => setIsJoining(false));
    };

    const handleLeave = () => {
        if (confirm('Êtes-vous sûr de vouloir quitter ce groupe?')) {
            setIsJoining(true);
            window.axios.post(`/api/v1/groupes/${id}/leave`)
                .then(() => {
                    setIsMember(false);
                    // Reload
                    return window.axios.get(`/api/v1/groupes/${id}`);
                })
                .then((res) => {
                    const data = res.data.data ?? res.data;
                    setMembres(data.utilisateurs || []);
                })
                .catch((err) => {
                    setError('Erreur lors du départ du groupe');
                })
                .finally(() => setIsJoining(false));
        }
    };

    if (loading) {
        return (
            <AppLayout>
                <div className="text-center py-12">
                    <svg className="animate-spin h-8 w-8 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </AppLayout>
        );
    }

    if (error || !groupe) {
        return (
            <AppLayout>
                <div className="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                    {error || 'Groupe non trouvé'}
                </div>
            </AppLayout>
        );
    }

    return (
        <AppLayout>
            <div className="space-y-6">
                <Link href="/groupes" className="text-blue-600 hover:text-blue-800">← Retour aux groupes</Link>

                <div className="bg-white rounded-lg shadow-sm p-6">
                    <div className="flex items-start justify-between mb-6">
                        <div className="flex items-start gap-4">
                            <div className="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-3xl">
                                {groupe.nom?.[0]?.toUpperCase()}
                            </div>
                            <div>
                                <h1 className="text-3xl font-bold text-gray-900">{groupe.nom}</h1>
                                <p className="text-gray-600 mt-1">{groupe.description}</p>
                                <div className="flex gap-4 mt-4 text-sm text-gray-600">
                                    <span>👥 {membres.length} membres</span>
                                    <span>📅 Créé le {new Date(groupe.created_at).toLocaleDateString('fr-FR')}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            {isMember ? (
                                <button
                                    onClick={handleLeave}
                                    disabled={isJoining}
                                    className="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition disabled:opacity-50"
                                >
                                    {isJoining ? 'Chargement...' : 'Quitter'}
                                </button>
                            ) : (
                                <button
                                    onClick={handleJoin}
                                    disabled={isJoining}
                                    className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                                >
                                    {isJoining ? 'Chargement...' : 'Rejoindre'}
                                </button>
                            )}
                        </div>
                    </div>
                </div>

                {isMember && (
                    <div className="bg-white rounded-lg shadow-sm p-6">
                        <h2 className="text-2xl font-bold text-gray-900 mb-4">Fil du groupe</h2>
                        <p className="text-gray-600">Les publications du groupe apparaitront ici (fonctionnalité à venir)</p>
                    </div>
                )}

                <div className="bg-white rounded-lg shadow-sm p-6">
                    <h2 className="text-2xl font-bold text-gray-900 mb-4">Membres ({membres.length})</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {membres.map((membre) => (
                            <div key={membre.id} className="flex items-center gap-3 p-3 border rounded-lg">
                                <div className="w-10 h-10 bg-blue-500 rounded-full text-white flex items-center justify-center font-semibold">
                                    {membre.nom?.[0]?.toUpperCase()}
                                </div>
                                <div className="flex-1">
                                    <p className="font-semibold text-gray-900">{membre.nom}</p>
                                    <p className="text-xs text-gray-500">{membre.filiere}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
