import React from 'react';
import { NavLink } from 'react-router-dom';
import { LayoutDashboard, Briefcase, Bookmark, FileText } from 'lucide-react';

const Sidebar = () => {
    const navItems = [
        { name: 'Dashboard', path: '/dashboard', icon: <LayoutDashboard size={20} /> },
        { name: 'Job Search', path: '/jobs', icon: <Briefcase size={20} /> },
        { name: 'Saved Jobs', path: '/saved', icon: <Bookmark size={20} /> },
        { name: 'Applications', path: '/applications', icon: <FileText size={20} /> },
    ];

    return (
        <aside className="w-64 bg-surface border-r border-gray-200 hidden md:block flex-shrink-0">
            <div className="p-6">
                <h2 className="text-2xl font-bold text-primary flex items-center gap-2">
                    <Briefcase className="text-primary" /> DevTrack
                </h2>
            </div>
            <nav className="mt-6">
                {navItems.map((item) => (
                    <NavLink
                        key={item.name}
                        to={item.path}
                        className={({ isActive }) =>
                            `flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-primary transition-colors ${
                                isActive ? 'bg-blue-50 text-primary border-r-4 border-primary font-medium' : ''
                            }`
                        }
                    >
                        <span className="mr-3">{item.icon}</span>
                        {item.name}
                    </NavLink>
                ))}
            </nav>
        </aside>
    );
};

export default Sidebar;
