import React from 'react';
import { MapPin, Building2, ExternalLink } from 'lucide-react';

const JobCard = ({ job, actionButton }) => {
    return (
        <div className="bg-surface rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div className="flex justify-between items-start mb-4">
                <div>
                    <h3 className="text-xl font-semibold text-gray-900">{job.title}</h3>
                    <div className="flex items-center gap-4 mt-2 text-sm text-gray-600">
                        <span className="flex items-center gap-1"><Building2 size={16} /> {job.company}</span>
                        {job.location && <span className="flex items-center gap-1"><MapPin size={16} /> {job.location}</span>}
                    </div>
                </div>
                {actionButton}
            </div>
            
            <p className="text-gray-600 text-sm line-clamp-3 mb-4">
                {job.description}
            </p>
            
            {job.apply_url && (
                <a 
                    href={job.apply_url} 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    className="inline-flex items-center gap-1 text-sm font-medium text-primary hover:text-primary-dark"
                >
                    Apply Externally <ExternalLink size={14} />
                </a>
            )}
        </div>
    );
};

export default JobCard;
