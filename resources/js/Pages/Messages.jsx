import React, { useEffect, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import AppLayout from '../Layouts/AppLayout';

export default function Messages() {
    const { auth } = usePage().props;
    const [conversations, setConversations] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [selectedConversation, setSelectedConversation] = useState(null);
    const [messageText, setMessageText] = useState('');
    const [messages, setMessages] = useState([]);
    const [sendingMessage, setSendingMessage] = useState(false);

    useEffect(() => {
        setLoading(true);
        setError(null);

        // Fetch conversations
        window.axios.get('/api/v1/conversations')
            .then((res) => {
                const data = res.data.data ?? res.data;
                setConversations(data || []);
                if (data && data.length > 0) {
                    setSelectedConversation(data[0]);
                    loadMessages(data[0].id);
                }
            })
            .catch((err) => {
                setError('Erreur lors du chargement des conversations');
                console.error(err);
            })
            .finally(() => setLoading(false));
    }, []);

    const loadMessages = (conversationId) => {
        window.axios.get(`/api/v1/conversations/${conversationId}/messages`)
            .then((res) => {
                const data = res.data.data ?? res.data;
                setMessages(data || []);
            })
            .catch((err) => console.error(err));
    };

    const sendMessage = (e) => {
        e.preventDefault();
        if (!messageText.trim() || !selectedConversation) return;

        setSendingMessage(true);

        window.axios.post(`/api/v1/conversations/${selectedConversation.id}/messages`, {
            contenu: messageText,
        })
            .then((res) => {
                const newMessage = res.data.data ?? res.data;
                setMessages([...messages, newMessage]);
                setMessageText('');
                // Scroll to bottom
                setTimeout(() => {
                    document.querySelector('.messages-container')?.scrollTo(0, document.querySelector('.messages-container')?.scrollHeight);
                }, 100);
            })
            .catch((err) => {
                console.error(err);
            })
            .finally(() => setSendingMessage(false));
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

    return (
        <AppLayout>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 h-96">
                {/* Conversations List */}
                <div className="bg-white rounded-lg shadow-sm overflow-hidden flex flex-col">
                    <div className="p-4 border-b">
                        <h2 className="font-semibold text-gray-900">Messages</h2>
                    </div>
                    <div className="overflow-y-auto flex-1">
                        {conversations.length === 0 ? (
                            <div className="p-4 text-center text-gray-500 text-sm">
                                Aucune conversation
                            </div>
                        ) : (
                            conversations.map((conv) => (
                                <button
                                    key={conv.id}
                                    onClick={() => {
                                        setSelectedConversation(conv);
                                        loadMessages(conv.id);
                                    }}
                                    className={`w-full p-3 border-b text-left hover:bg-gray-50 transition ${
                                        selectedConversation?.id === conv.id ? 'bg-blue-50' : ''
                                    }`}
                                >
                                    <p className="font-semibold text-sm text-gray-900">{conv.titre}</p>
                                    <p className="text-xs text-gray-500 truncate">{conv.dernier_message}</p>
                                </button>
                            ))
                        )}
                    </div>
                </div>

                {/* Chat Area */}
                <div className="md:col-span-2 bg-white rounded-lg shadow-sm flex flex-col">
                    {selectedConversation ? (
                        <>
                            {/* Header */}
                            <div className="p-4 border-b">
                                <h3 className="font-semibold text-gray-900">{selectedConversation.titre}</h3>
                            </div>

                            {/* Messages */}
                            <div className="messages-container flex-1 overflow-y-auto p-4 space-y-4">
                                {messages.map((msg) => (
                                    <div
                                        key={msg.id}
                                        className={`flex ${msg.expediteur_id === auth?.user?.id ? 'justify-end' : 'justify-start'}`}
                                    >
                                        <div
                                            className={`px-4 py-2 rounded-lg max-w-xs ${
                                                msg.expediteur_id === auth?.user?.id
                                                    ? 'bg-blue-600 text-white'
                                                    : 'bg-gray-100 text-gray-900'
                                            }`}
                                        >
                                            <p className="text-sm">{msg.contenu}</p>
                                            <p className="text-xs mt-1 opacity-75">
                                                {new Date(msg.created_at).toLocaleTimeString('fr-FR', {
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                })}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            {/* Input */}
                            <form onSubmit={sendMessage} className="p-4 border-t flex gap-2">
                                <input
                                    type="text"
                                    placeholder="Écrivez un message..."
                                    value={messageText}
                                    onChange={(e) => setMessageText(e.target.value)}
                                    className="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                <button
                                    type="submit"
                                    disabled={sendingMessage || !messageText.trim()}
                                    className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:bg-gray-400"
                                >
                                    📤
                                </button>
                            </form>
                        </>
                    ) : (
                        <div className="flex items-center justify-center h-full text-gray-500">
                            Sélectionnez une conversation
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
