import React, { useEffect, useState } from 'react';
import { Link } from '@inertiajs/react';
import AppLayout from '../Layouts/AppLayout';
import PublicationCard from '../Components/PublicationCard';

export default function Feed() {
    const [publications, setPublications] = useState([]);
    const [loading, setLoading] = useState(true);
    const [page, setPage] = useState(1);
    const [hasMore, setHasMore] = useState(false);
    const [error, setError] = useState(null);

    useEffect(() => {
        setLoading(true);
        setError(null);

        window.axios.get(`/api/v1/publications?page=${page}`)
            .then((res) => {
                const data = res.data.data ?? res.data;
                setPublications(page === 1 ? (data || []) : [...publications, ...(data || [])]);
                setHasMore(res.data.next_page_url ? true : false);
            })
            .catch((err) => {
                setError('Erreur lors du chargement des publications');
                console.error(err);
            })
            .finally(() => setLoading(false));
    }, [page]);

    const loadMore = () => {
        setPage(page + 1);
    };

    return (
        <AppLayout>
            <div className="space-y-6">
                <div className="flex justify-between items-center">
                    <h1 className="text-3xl font-bold text-gray-900">Fil d'actualités</h1>
                    <Link
                        href="/publications/create"
                        className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                    >
                        Créer une publication
                    </Link>
                </div>

                {error && (
                    <div className="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                        {error}
                    </div>
                )}

                {loading && page === 1 && (
                    <div className="text-center py-12">
                        <div className="inline-block">
                            <svg className="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p className="mt-2 text-gray-600">Chargement des publications...</p>
                    </div>
                )}

                {!loading && publications.length === 0 && (
                    <div className="text-center py-12">
                        <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p className="mt-2 text-gray-600">Aucune publication pour le moment.</p>
                    </div>
                )}

                <div className="space-y-4">
                    {publications.map((p) => (
                        <PublicationCard key={p.id} publication={p} onReactionChange={() => {}} />
                    ))}
                </div>

                {!loading && hasMore && (
                    <div className="text-center">
                        <button
                            onClick={loadMore}
                            className="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
                        >
                            Charger plus
                        </button>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
