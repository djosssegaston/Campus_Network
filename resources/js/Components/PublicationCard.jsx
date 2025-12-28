import React, { useState } from 'react';

const reactionEmojis = {
    like: '👍',
    love: '❤️',
    haha: '😂',
    wow: '😮',
    sad: '😢',
    angry: '😠',
};

export default function PublicationCard({ publication, onReactionChange }) {
    const [reactionsCount, setReactionsCount] = useState(publication.reactions_count || 0);
    const [userReaction, setUserReaction] = useState(publication.user_reaction || null);
    const [showReactionPicker, setShowReactionPicker] = useState(false);
    const [loadingReaction, setLoadingReaction] = useState(false);

    const toggleReaction = (type) => {
        const url = `/api/v1/publications/${publication.id}/reactions`;
        setLoadingReaction(true);

        if (userReaction === type) {
            window.axios.delete(url)
                .then(() => {
                    setUserReaction(null);
                    setReactionsCount(Math.max(0, reactionsCount - 1));
                    setShowReactionPicker(false);
                    onReactionChange?.();
                })
                .finally(() => setLoadingReaction(false));
            return;
        }

        window.axios.post(url, { type })
            .then(() => {
                setUserReaction(type);
                if (!userReaction) setReactionsCount(reactionsCount + 1);
                setShowReactionPicker(false);
                onReactionChange?.();
            })
            .finally(() => setLoadingReaction(false));
    };

    const authorName = publication.utilisateur?.nom ?? publication.utilisateur?.name ?? 'Utilisateur';
    const authorInitial = authorName[0]?.toUpperCase() || '?';
    const createdDate = new Date(publication.created_at);
    const timeAgo = getTimeAgo(createdDate);

    return (
        <div className="bg-white rounded-lg shadow-sm hover:shadow-md transition p-6">
            {/* Header */}
            <div className="flex items-start gap-3 mb-4">
                <div className="w-12 h-12 bg-blue-500 rounded-full text-white flex items-center justify-center text-lg font-semibold">
                    {authorInitial}
                </div>
                <div className="flex-1">
                    <h3 className="font-semibold text-gray-900">{authorName}</h3>
                    <p className="text-xs text-gray-500">{timeAgo}</p>
                </div>
                {publication.visibilite === 'prive' && (
                    <span className="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">Privé</span>
                )}
            </div>

            {/* Content */}
            <p className="text-gray-700 mb-4 leading-relaxed">{publication.contenu}</p>

            {/* Medias if any */}
            {publication.medias && publication.medias.length > 0 && (
                <div className="mb-4 rounded-lg overflow-hidden bg-gray-100">
                    {publication.medias.map((media) => (
                        <div key={media.id} className="p-4 text-center text-gray-500">
                            📎 {media.nom_fichier}
                        </div>
                    ))}
                </div>
            )}

            {/* Footer - Reactions */}
            <div className="pt-3 border-t border-gray-100">
                <div className="flex items-center justify-between text-sm">
                    <div className="flex items-center gap-2">
                        {reactionsCount > 0 && (
                            <div className="flex items-center gap-1">
                                {userReaction && <span>{reactionEmojis[userReaction] || '👍'}</span>}
                                <span className="text-gray-600">{reactionsCount}</span>
                            </div>
                        )}
                    </div>
                </div>

                {/* Action Buttons */}
                <div className="flex gap-2 mt-3 relative">
                    <div className="relative flex-1">
                        <button
                            onClick={() => setShowReactionPicker(!showReactionPicker)}
                            disabled={loadingReaction}
                            className={`w-full px-3 py-2 rounded-lg transition flex items-center justify-center gap-2 ${
                                userReaction
                                    ? 'bg-blue-50 text-blue-600 font-medium'
                                    : 'text-gray-600 hover:bg-gray-100'
                            } disabled:opacity-50`}
                        >
                            {userReaction ? (
                                <>
                                    <span>{reactionEmojis[userReaction] || '👍'}</span>
                                    <span className="hidden sm:inline">Réagi</span>
                                </>
                            ) : (
                                <>
                                    <span>👍</span>
                                    <span className="hidden sm:inline">Réagir</span>
                                </>
                            )}
                        </button>

                        {/* Reaction Picker Popup */}
                        {showReactionPicker && (
                            <div className="absolute bottom-full left-0 mb-2 bg-white border border-gray-200 rounded-lg shadow-lg p-2 flex gap-1">
                                {Object.entries(reactionEmojis).map(([type, emoji]) => (
                                    <button
                                        key={type}
                                        onClick={() => toggleReaction(type)}
                                        className={`text-2xl p-1 rounded hover:bg-gray-100 transition ${
                                            userReaction === type ? 'bg-gray-100 ring-2 ring-blue-500' : ''
                                        }`}
                                        title={type}
                                    >
                                        {emoji}
                                    </button>
                                ))}
                            </div>
                        )}
                    </div>

                    <button className="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition flex-1 text-center">
                        💬 Commenter
                    </button>
                </div>
            </div>
        </div>
    );
}

function getTimeAgo(date) {
    const seconds = Math.floor((new Date() - date) / 1000);
    if (seconds < 60) return 'À l\'instant';
    if (seconds < 3600) return `Il y a ${Math.floor(seconds / 60)}m`;
    if (seconds < 86400) return `Il y a ${Math.floor(seconds / 3600)}h`;
    if (seconds < 2592000) return `Il y a ${Math.floor(seconds / 86400)}j`;
    return date.toLocaleDateString('fr-FR');
}
