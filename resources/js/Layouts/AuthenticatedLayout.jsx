import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import { 
    LayoutDashboard, 
    FileText, 
    FolderKanban, 
    Image as ImageIcon, 
    Users, 
    Settings, 
    Menu, 
    X, 
    Bell, 
    User, 
    LogOut, 
    Search,
    ChevronRight,
    Globe
} from 'lucide-react';
import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

function cn(...inputs) {
    return twMerge(clsx(inputs));
}

export default function AuthenticatedLayout({ children, header }) {
    const { auth } = usePage().props;
    const [isSidebarOpen, setIsSidebarOpen] = useState(true);
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

    const navigation = [
        { name: 'Dashboard', href: '/admin/dashboard', icon: LayoutDashboard },
        { name: 'Notícias', href: '/admin/noticias', icon: FileText },
        { name: 'Projetos', href: '/admin/projetos', icon: FolderKanban },
        { name: 'Galeria', href: '/admin/galeria', icon: ImageIcon },
        { name: 'Equipe', href: '/admin/equipe', icon: Users },
        { name: 'Transparência', href: '/admin/transparencia', icon: Globe },
        { name: 'Configurações', href: '/admin/settings', icon: Settings },
    ];

    return (
        <div className="min-h-screen bg-[#f8fafc] flex overflow-hidden">
            {/* Sidebar Desktop */}
            <motion.aside 
                initial={false}
                animate={{ width: isSidebarOpen ? 280 : 80 }}
                className="hidden lg:flex flex-col bg-white border-r border-gray-100 relative z-20 shadow-xl shadow-gray-200/50"
            >
                <div className="p-6 flex items-center justify-between">
                    <AnimatePresence mode="wait">
                        {isSidebarOpen && (
                            <motion.div 
                                initial={{ opacity: 0, x: -10 }}
                                animate={{ opacity: 1, x: 0 }}
                                exit={{ opacity: 0, x: -10 }}
                                className="flex items-center gap-3"
                            >
                                <div className="w-10 h-10 bg-green-700 rounded-xl flex items-center justify-center text-white shadow-lg shadow-green-200">
                                    <span className="font-black text-xl">I</span>
                                </div>
                                <span className="font-black text-gray-900 tracking-tighter text-xl">ISSM <span className="text-green-700">CRM</span></span>
                            </motion.div>
                        )}
                    </AnimatePresence>
                    <button 
                        onClick={() => setIsSidebarOpen(!isSidebarOpen)}
                        className="p-2 hover:bg-gray-50 rounded-lg transition-colors text-gray-400 hover:text-green-700"
                    >
                        <Menu size={20} />
                    </button>
                </div>

                <nav className="flex-1 px-4 py-4 space-y-2">
                    {navigation.map((item) => (
                        <Link
                            key={item.name}
                            href={item.href}
                            className={cn(
                                "flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300 group relative",
                                usePage().url.startsWith(item.href) 
                                    ? "bg-green-50 text-green-700" 
                                    : "text-gray-500 hover:bg-gray-50 hover:text-gray-900"
                            )}
                        >
                            <item.icon size={22} className={cn(
                                "transition-transform group-hover:scale-110",
                                usePage().url.startsWith(item.href) ? "text-green-700" : "text-gray-400 group-hover:text-green-600"
                            )} />
                            {isSidebarOpen && (
                                <span className="font-bold text-sm tracking-tight">{item.name}</span>
                            )}
                            {usePage().url.startsWith(item.href) && (
                                <motion.div 
                                    layoutId="active-pill"
                                    className="absolute left-0 w-1 h-6 bg-green-700 rounded-r-full"
                                />
                            )}
                        </Link>
                    ))}
                </nav>

                <div className="p-4 border-t border-gray-50">
                    <div className={cn(
                        "flex items-center gap-3 p-3 rounded-2xl bg-gray-50",
                        !isSidebarOpen && "justify-center"
                    )}>
                        <div className="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-green-700 font-bold border border-gray-100">
                            {auth.user.name.charAt(0)}
                        </div>
                        {isSidebarOpen && (
                            <div className="min-w-0">
                                <p className="text-sm font-black text-gray-900 truncate">{auth.user.name}</p>
                                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Administrador</p>
                            </div>
                        )}
                    </div>
                </div>
            </motion.aside>

            {/* Main Content */}
            <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
                <header className="h-20 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-8 relative z-10">
                    <div className="flex items-center gap-4">
                        <h2 className="text-xl font-black text-gray-900 tracking-tight">{header}</h2>
                    </div>

                    <div className="flex items-center gap-4">
                        <div className="hidden md:flex items-center bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 w-64 focus-within:ring-2 focus-within:ring-green-500/20 transition-all">
                            <Search size={18} className="text-gray-400" />
                            <input 
                                type="text" 
                                placeholder="Buscar no sistema..." 
                                className="bg-transparent border-none focus:ring-0 text-sm w-full ml-2 font-medium"
                            />
                        </div>
                        
                        <button className="relative p-2.5 bg-gray-50 text-gray-500 hover:text-green-700 hover:bg-green-50 rounded-xl transition-all">
                            <Bell size={20} />
                            <span className="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>

                        <div className="h-8 w-px bg-gray-100 mx-2"></div>

                        <Link 
                            href={route('home')} 
                            target="_blank"
                            className="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-green-700 transition-colors"
                        >
                            <Globe size={18} />
                            <span className="hidden sm:inline">Ver Site</span>
                        </Link>
                    </div>
                </header>

                <main className="flex-1 overflow-y-auto p-8 bg-[#f8fafc]">
                    <motion.div
                        initial={{ opacity: 0, y: 10 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.4 }}
                    >
                        {children}
                    </motion.div>
                </main>
            </div>
        </div>
    );
}
