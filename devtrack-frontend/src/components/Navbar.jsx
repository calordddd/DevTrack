import React, { useContext } from 'react';
import { AuthContext } from '../context/AuthContext';
import { LogOut, User } from 'lucide-react';

const Navbar = () => {
    const { user, logout } = useContext(AuthContext);

    return (
        <header className="bg-surface border-b border-gray-200 h-16 flex items-center justify-between px-6">
            <div className="md:hidden text-xl font-bold text-primary">DevTrack</div>
            <div className="hidden md:block text-lg font-medium text-gray-700">Welcome, {user?.name}</div>
            
            <div className="flex items-center gap-4">
                <div className="flex items-center gap-2 text-sm text-gray-600">
                    <div className="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        <User size={16} />
                    </div>
                    <span className="hidden sm:block">{user?.email}</span>
                </div>
                <button 
                    onClick={logout}
                    className="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors"
                    title="Logout"
                >
                    <LogOut size={20} />
                </button>
            </div>
        </header>
    );
};

export default Navbar;
