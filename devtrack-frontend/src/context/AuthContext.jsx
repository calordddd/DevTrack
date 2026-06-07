import React, { createContext, useState, useEffect } from 'react';
import api from '../services/api';

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchUser = async () => {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                setLoading(false);
                return;
            }

            try {
                const response = await api.get('/user');
                setUser(response.data);
            } catch (error) {
                console.error("Failed to fetch user:", error);
                localStorage.removeItem('auth_token');
            } finally {
                setLoading(false);
            }
        };

        fetchUser();
    }, []);

    const login = async (email, password) => {
        const response = await api.post('/login', { email, password });
        localStorage.setItem('auth_token', response.data.access_token);
        setUser(response.data.user);
    };

    const register = async (name, email, password) => {
        const response = await api.post('/register', { name, email, password });
        localStorage.setItem('auth_token', response.data.access_token);
        setUser(response.data.user);
    };

    const logout = async () => {
        await api.post('/logout');
        localStorage.removeItem('auth_token');
        setUser(null);
    };

    return (
        <AuthContext.Provider value={{ user, loading, login, register, logout }}>
            {children}
        </AuthContext.Provider>
    );
};
