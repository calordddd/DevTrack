import React, { useState, useEffect } from 'react';
import api from '../services/api';
import JobCard from '../components/JobCard';
import LoadingSpinner from '../components/LoadingSpinner';

const SavedJobs = () => {
    const [savedJobs, setSavedJobs] = useState([]);
    const [loading, setLoading] = useState(true);

    const fetchSavedJobs = async () => {
        try {
            const response = await api.get('/saved-jobs');
            setSavedJobs(response.data.data);
        } catch (err) {
            console.error(err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchSavedJobs();
    }, []);

    const handleRemove = async (id) => {
        try {
            await api.delete(`/saved-jobs/${id}`);
            setSavedJobs(savedJobs.filter(sj => sj.id !== id));
        } catch (err) {
            console.error(err);
        }
    };

    const handleApply = async (savedJob) => {
        try {
            await api.post('/applications', { job: savedJob.job, status: 'applied' });
            await handleRemove(savedJob.id); // Remove from saved if tracked as applied
            alert('Moved to Applications!');
        } catch (err) {
            console.error(err);
        }
    };

    if (loading) return <LoadingSpinner />;

    return (
        <div>
            <h1 className="text-3xl font-bold text-gray-900 mb-6">Saved Jobs</h1>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {savedJobs.length > 0 ? savedJobs.map((savedJob) => (
                    <JobCard 
                        key={savedJob.id} 
                        job={savedJob.job} 
                        actionButton={
                            <div className="flex flex-col gap-2">
                                <button 
                                    onClick={() => handleApply(savedJob)}
                                    className="text-sm px-3 py-1 bg-primary text-white hover:bg-primary-dark rounded"
                                >
                                    Track App
                                </button>
                                <button 
                                    onClick={() => handleRemove(savedJob.id)}
                                    className="text-sm px-3 py-1 border border-red-500 text-red-500 hover:bg-red-50 rounded"
                                >
                                    Remove
                                </button>
                            </div>
                        }
                    />
                )) : (
                    <p className="text-gray-500">You haven't saved any jobs yet.</p>
                )}
            </div>
        </div>
    );
};

export default SavedJobs;
