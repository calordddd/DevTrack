import React, { useState, useEffect } from 'react';
import api from '../services/api';
import JobCard from '../components/JobCard';
import LoadingSpinner from '../components/LoadingSpinner';
import { Search } from 'lucide-react';

const JobSearch = () => {
    const [jobs, setJobs] = useState([]);
    const [loading, setLoading] = useState(false);
    const [query, setQuery] = useState('');
    const [actionLoading, setActionLoading] = useState(null);

    const searchJobs = async (e) => {
        if (e) e.preventDefault();
        setLoading(true);
        try {
            const response = await api.get(`/jobs/search?q=${query}`);
            setJobs(response.data.data);
        } catch (err) {
            console.error(err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        searchJobs();
        // eslint-disable-next-line
    }, []);

    const handleSaveJob = async (job) => {
        setActionLoading(job.external_job_id);
        try {
            await api.post('/saved-jobs', { job });
            alert('Job saved successfully!');
        } catch (err) {
            console.error(err);
            alert('Failed to save job');
        } finally {
            setActionLoading(null);
        }
    };

    const handleApply = async (job) => {
        setActionLoading(job.external_job_id);
        try {
            await api.post('/applications', { job, status: 'applied' });
            alert('Application tracked successfully!');
        } catch (err) {
            console.error(err);
            alert('Failed to track application');
        } finally {
            setActionLoading(null);
        }
    };

    return (
        <div>
            <h1 className="text-3xl font-bold text-gray-900 mb-6">Find Jobs</h1>
            
            <form onSubmit={searchJobs} className="mb-8">
                <div className="relative max-w-2xl">
                    <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <Search className="h-5 w-5 text-gray-400" />
                    </div>
                    <input
                        type="text"
                        className="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                        placeholder="Search for titles, companies, or locations..."
                        value={query}
                        onChange={(e) => setQuery(e.target.value)}
                    />
                    <button type="submit" className="absolute inset-y-1 right-1 px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
                        Search
                    </button>
                </div>
            </form>

            {loading ? (
                <LoadingSpinner />
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {jobs.length > 0 ? jobs.map((job) => (
                        <JobCard 
                            key={job.external_job_id} 
                            job={job} 
                            actionButton={
                                <div className="flex flex-col gap-2">
                                    <button 
                                        disabled={actionLoading === job.external_job_id}
                                        onClick={() => handleSaveJob(job)}
                                        className="text-sm px-3 py-1 border border-primary text-primary hover:bg-blue-50 rounded"
                                    >
                                        Save Job
                                    </button>
                                    <button 
                                        disabled={actionLoading === job.external_job_id}
                                        onClick={() => handleApply(job)}
                                        className="text-sm px-3 py-1 bg-primary text-white hover:bg-primary-dark rounded"
                                    >
                                        Track App
                                    </button>
                                </div>
                            }
                        />
                    )) : (
                        <p className="text-gray-500">No jobs found matching your criteria.</p>
                    )}
                </div>
            )}
        </div>
    );
};

export default JobSearch;
