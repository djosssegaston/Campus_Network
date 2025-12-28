import React, { useEffect, useState } from 'react';
import AppLayout from '../Layouts/AppLayout';

export default function Admin() {
    const [stats, setStats] = useState(null);
    const [users, setUsers] = useState([]);
    const [publications, setPublications] = useState([]);
    const [loading, setLoading] = useState(true);
    const [activeTab, setActiveTab] = useState('dashboard');

    useEffect(() => {
        // Fetch admin dashboard stats
        Promise.all([
            window.axios.get('/api/v1/admin/stats'),
            window.axios.get('/api/v1/admin/users'),
            window.axios.get('/api/v1/admin/publications/pending'),
        ])
            .then(([statsRes, usersRes, pubsRes]) => {
                setStats(statsRes.data.data ?? statsRes.data);
                setUsers(usersRes.data.data ?? usersRes.data ?? []);
                setPublications(pubsRes.data.data ?? pubsRes.data ?? []);
            })
            .catch((err) => console.error(err))
            .finally(() => setLoading(false));
    }, []);

    return (
        <AppLayout>
            <div className="space-y-6">
                <h1 className="text-3xl font-bold text-gray-900">Tableau de bord Admin</h1>

                {/* Tabs */}
                <div className="flex gap-4 border-b">
                    {['dashboard', 'users', 'moderation'].map((tab) => (
                        <button
                            key={tab}
                            onClick={() => setActiveTab(tab)}
                            className={`px-4 py-2 font-medium border-b-2 transition ${
                                activeTab === tab
                                    ? 'border-blue-600 text-blue-600'
                                    : 'border-transparent text-gray-600 hover:text-gray-900'
                            }`}
                        >
                            {tab === 'dashboard'
                                ? 'Tableau de bord'
                                : tab === 'users'
                                ? 'Utilisateurs'
                                : 'Modération'}
                        </button>
                    ))}
                </div>

                {/* Dashboard Tab */}
                {activeTab === 'dashboard' && stats && (
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                        {[
                            { label: 'Utilisateurs', value: stats.total_users, icon: '👥' },
                            { label: 'Publications', value: stats.total_publications, icon: '📝' },
                            { label: 'Commentaires', value: stats.total_comments, icon: '💬' },
                            { label: 'Groupes', value: stats.total_groups, icon: '👫' },
                        ].map((stat, i) => (
                            <div key={i} className="bg-white rounded-lg shadow-sm p-6">
                                <div className="text-3xl mb-2">{stat.icon}</div>
                                <p className="text-gray-600 text-sm">{stat.label}</p>
                                <p className="text-2xl font-bold text-gray-900 mt-2">{stat.value || 0}</p>
                            </div>
                        ))}
                    </div>
                )}

                {/* Users Tab */}
                {activeTab === 'users' && (
                    <div className="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div className="p-6 border-b">
                            <h2 className="text-xl font-semibold text-gray-900">Utilisateurs</h2>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Nom</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Email</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Filière</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Groupe</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y">
                                    {users.length === 0 ? (
                                        <tr>
                                            <td colSpan="5" className="px-6 py-4 text-center text-gray-500">
                                                Aucun utilisateur
                                            </td>
                                        </tr>
                                    ) : (
                                        users.map((user) => (
                                            <tr key={user.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 font-semibold text-gray-900">{user.nom}</td>
                                                <td className="px-6 py-4 text-gray-600 text-sm">{user.email}</td>
                                                <td className="px-6 py-4 text-gray-600">{user.filiere}</td>
                                                <td className="px-6 py-4">{user.annee_etude}</td>
                                                <td className="px-6 py-4">
                                                    <button className="text-red-600 hover:text-red-800 text-sm">Bloquer</button>
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                )}

                {/* Moderation Tab */}
                {activeTab === 'moderation' && (
                    <div className="bg-white rounded-lg shadow-sm p-6">
                        <h2 className="text-xl font-semibold text-gray-900 mb-4">Publications en attente de modération</h2>
                        <div className="space-y-4">
                            {publications.length === 0 ? (
                                <p className="text-gray-500">Aucune publication à modérer</p>
                            ) : (
                                publications.map((pub) => (
                                    <div key={pub.id} className="border rounded-lg p-4">
                                        <div className="flex justify-between items-start">
                                            <div>
                                                <p className="font-semibold text-gray-900">{pub.utilisateur?.nom}</p>
                                                <p className="text-gray-600 mt-2">{pub.contenu}</p>
                                            </div>
                                            <div className="flex gap-2">
                                                <button className="px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200">
                                                    ✓ Approuver
                                                </button>
                                                <button className="px-3 py-1 bg-red-100 text-red-700 rounded text-sm hover:bg-red-200">
                                                    ✕ Rejeter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                ))
                            )}
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
