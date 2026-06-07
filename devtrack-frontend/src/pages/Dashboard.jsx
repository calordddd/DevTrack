import React, { useEffect, useState } from 'react';
import api from '../services/api';
import DashboardStats from '../components/DashboardStats';
import LoadingSpinner from '../components/LoadingSpinner';
import { Link } from 'react-router-dom';

const Dashboard = () => {
    const [stats, setStats] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchStats = async () => {
            try {
                const response = await api.get('/dashboard/stats');
                setStats(response.data.data);
            } catch (err) {
                console.error(err);
            } finally {
                setLoading(false);
            }
        };

        fetchStats();
    }, []);

    if (loading) return <LoadingSpinner />;

    return (
        <div>
            <h1 className="text-3xl font-bold text-gray-900 mb-6">Dashboard Overview</h1>
            {stats && <DashboardStats stats={stats} />}
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <div className="bg-surface p-6 rounded-xl shadow-sm border border-gray-100">
                    <h2 className="text-xl font-semibold mb-4">Quick Actions</h2>
                    <div className="flex gap-4">
                        <Link to="/jobs" className="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">Find Jobs</Link>
                        <Link to="/applications" className="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">View Applications</Link>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Dashboard;
