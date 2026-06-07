import React, { useState, useEffect } from 'react';
import api from '../services/api';
import ApplicationCard from '../components/ApplicationCard';
import LoadingSpinner from '../components/LoadingSpinner';

const Applications = () => {
    const [applications, setApplications] = useState([]);
    const [loading, setLoading] = useState(true);

    const fetchApplications = async () => {
        try {
            const response = await api.get('/applications');
            setApplications(response.data.data);
        } catch (err) {
            console.error(err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchApplications();
    }, []);

    const handleStatusChange = async (id, newStatus) => {
        try {
            const response = await api.put(`/applications/${id}`, { status: newStatus });
            setApplications(applications.map(app => app.id === id ? response.data.data : app));
        } catch (err) {
            console.error(err);
        }
    };

    const handleDelete = async (id) => {
        if (!window.confirm('Are you sure you want to delete this application?')) return;
        try {
            await api.delete(`/applications/${id}`);
            setApplications(applications.filter(app => app.id !== id));
        } catch (err) {
            console.error(err);
        }
    };

    if (loading) return <LoadingSpinner />;

    return (
        <div>
            <h1 className="text-3xl font-bold text-gray-900 mb-6">My Applications</h1>
            
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {applications.length > 0 ? applications.map((app) => (
                    <ApplicationCard 
                        key={app.id}
                        application={app}
                        onStatusChange={handleStatusChange}
                        onDelete={handleDelete}
                    />
                )) : (
                    <p className="text-gray-500">No applications tracked yet.</p>
                )}
            </div>
        </div>
    );
};

export default Applications;
