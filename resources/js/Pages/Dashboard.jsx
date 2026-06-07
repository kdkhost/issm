import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { 
    TrendingUp, 
    MessageSquare, 
    Newspaper, 
    Calendar,
    ArrowUpRight,
    Plus,
    FileText
} from 'lucide-react';
import { motion } from 'framer-motion';

export default function Dashboard({ stats, recentContacts, recentNews }) {
    const cards = [
        { title: 'Notícias', value: stats.news, icon: Newspaper, color: 'text-green-600', bg: 'bg-green-100', link: '/admin/noticias' },
        { title: 'Projetos', value: stats.projects, icon: FileText, color: 'text-blue-600', bg: 'bg-blue-100', link: '/admin/projetos' },
        { title: 'Mensagens', value: stats.contacts, icon: MessageSquare, color: 'text-red-600', bg: 'bg-red-100', link: '/admin/contatos' },
        { title: 'Fotos', value: stats.gallery, icon: TrendingUp, color: 'text-orange-600', bg: 'bg-orange-100', link: '/admin/galeria' },
    ];

    return (
        <AuthenticatedLayout header="Dashboard">
            <Head title="Dashboard" />

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                {cards.map((card, idx) => (
                    <motion.div
                        key={card.title}
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ delay: idx * 0.1 }}
                        className="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500 group"
                    >
                        <div className="flex items-center justify-between mb-4">
                            <div className={`w-14 h-14 ${card.bg} rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110 duration-500`}>
                                <card.icon className={card.color} size={28} />
                            </div>
                            <Link href={card.link} className="p-2 bg-gray-50 rounded-lg text-gray-400 hover:text-gray-900 transition-colors">
                                <ArrowUpRight size={20} />
                            </Link>
                        </div>
                        <div>
                            <p className="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">{card.title}</p>
                            <h4 className="text-4xl font-black text-gray-900">{card.value}</h4>
                        </div>
                    </motion.div>
                ))}
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {/* Mensagens Recentes */}
                <div className="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <div className="flex items-center justify-between mb-8">
                        <h3 className="text-xl font-black text-gray-900 tracking-tight">Mensagens Recentes</h3>
                        <Link href="/admin/contatos" className="text-sm font-bold text-green-700 hover:underline">Ver todas</Link>
                    </div>
                    <div className="space-y-4">
                        {recentContacts.map((contact, idx) => (
                            <motion.div 
                                key={contact.id}
                                initial={{ opacity: 0, x: -20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.3 + (idx * 0.1) }}
                                className="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-transparent hover:border-green-100 hover:bg-white transition-all duration-300 group"
                            >
                                <div className="flex items-center gap-4 min-w-0">
                                    <div className="w-12 h-12 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-green-700 font-bold shadow-sm">
                                        {contact.name.charAt(0)}
                                    </div>
                                    <div className="min-w-0">
                                        <p className="font-bold text-gray-900 truncate">{contact.name}</p>
                                        <p className="text-xs text-gray-400 font-medium truncate">{contact.subject}</p>
                                    </div>
                                </div>
                                <Link href={`/admin/contatos/${contact.id}`} className="opacity-0 group-hover:opacity-100 p-2 bg-green-50 text-green-700 rounded-lg transition-all">
                                    <ChevronRight size={18} />
                                </Link>
                            </motion.div>
                        ))}
                    </div>
                </div>

                {/* Ações Rápidas */}
                <div className="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm relative overflow-hidden">
                    <div className="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-full blur-3xl -mr-16 -mt-16"></div>
                    <h3 className="text-xl font-black text-gray-900 tracking-tight mb-8 relative z-10">Ações Rápidas</h3>
                    <div className="grid grid-cols-2 gap-4 relative z-10">
                        {[
                            { name: 'Nova Notícia', icon: Plus, href: '/admin/noticias/create', bg: 'bg-green-700', text: 'text-white' },
                            { name: 'Novo Projeto', icon: Plus, href: '/admin/projetos/create', bg: 'bg-blue-600', text: 'text-white' },
                            { name: 'Upload Foto', icon: Newspaper, href: '/admin/galeria/create', bg: 'bg-gray-900', text: 'text-white' },
                            { name: 'Configurações', icon: Settings, href: '/admin/settings', bg: 'bg-white', text: 'text-gray-900', border: 'border-gray-200' },
                        ].map((action) => (
                            <Link 
                                key={action.name}
                                href={action.href}
                                className={`flex flex-col items-center justify-center p-6 rounded-3xl ${action.bg} ${action.text} ${action.border || ''} hover:scale-[1.02] transition-transform shadow-lg shadow-gray-200/20`}
                            >
                                <action.icon size={24} className="mb-2" />
                                <span className="text-xs font-black uppercase tracking-widest">{action.name}</span>
                            </Link>
                        ))}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
