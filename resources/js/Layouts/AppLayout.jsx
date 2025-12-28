import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function AppLayout({ children }) {
    const { auth } = usePage().props;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Navbar */}
            <nav className="bg-white shadow-sm sticky top-0 z-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-16">
                        <div className="flex items-center gap-2">
                            <div className="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
                                C
                            </div>
                            <h1 className="text-xl font-bold text-gray-900">Campus Network</h1>
                        </div>

                        {/* Desktop Menu */}
                        <div className="hidden md:flex items-center gap-6">
                            <Link href="/feed" className="text-gray-700 hover:text-blue-600">
                                Fil d'actualités
                            </Link>
                            <Link href="/groupes" className="text-gray-700 hover:text-blue-600">
                                Groupes
                            </Link>
                            <Link href="/publications/create" className="text-gray-700 hover:text-blue-600">
                                Créer
                            </Link>

                            {auth?.user ? (
                                <div className="flex items-center gap-4 border-l pl-6">
                                    <Link href="/messages" className="text-gray-700 hover:text-blue-600">
                                        💬
                                    </Link>
                                    {auth.user.role?.slug === 'administrateur' && (
                                        <Link href="/admin" className="text-gray-700 hover:text-blue-600">
                                            ⚙️
                                        </Link>
                                    )}
                                    <span className="text-sm text-gray-600">{auth.user.nom || auth.user.name}</span>
                                    <Link
                                        href="/profile"
                                        className="w-8 h-8 bg-blue-500 rounded-full text-white flex items-center justify-center text-sm font-semibold"
                                    >
                                        {(auth.user.nom || auth.user.name)[0]?.toUpperCase()}
                                    </Link>
                                </div>
                            ) : (
                                <div className="flex gap-2">
                                    <Link href="/login" className="text-gray-700 hover:text-blue-600">
                                        Connexion
                                    </Link>
                                    <Link href="/register" className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                        Inscription
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Mobile Menu Button */}
                        <button
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                            className="md:hidden text-gray-600 hover:text-gray-900"
                        >
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    {/* Mobile Menu */}
                    {mobileMenuOpen && (
                        <div className="md:hidden pb-4 border-t">
                            <Link href="/feed" className="block px-0 py-2 text-gray-700 hover:text-blue-600">
                                Fil d'actualités
                            </Link>
                            <Link href="/groupes" className="block px-0 py-2 text-gray-700 hover:text-blue-600">
                                Groupes
                            </Link>
                            <Link href="/publications/create" className="block px-0 py-2 text-gray-700 hover:text-blue-600">
                                Créer
                            </Link>
                            {auth?.user && (
                                <>
                                    <Link href="/messages" className="block px-0 py-2 text-gray-700 hover:text-blue-600">
                                        Messages
                                    </Link>
                                    {auth.user.role?.slug === 'administrateur' && (
                                        <Link href="/admin" className="block px-0 py-2 text-gray-700 hover:text-blue-600">
                                            Admin
                                        </Link>
                                    )}
                                </>
                            )}
                        </div>
                    )}
                </div>
            </nav>

            {/* Main Content */}
            <main className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {children}
            </main>

            {/* Footer */}
            <footer className="bg-gray-100 border-t mt-12">
                <div className="max-w-7xl mx-auto px-4 py-6 text-center text-gray-600">
                    <p>&copy; 2024 Campus Network. Tous droits réservés.</p>
                </div>
            </footer>
        </div>
    );
}
