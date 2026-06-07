import React from 'react';
import JobCard from './JobCard';

const statusColors = {
    saved: 'bg-gray-100 text-gray-800',
    applied: 'bg-blue-100 text-blue-800',
    interview: 'bg-purple-100 text-purple-800',
    offer: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800',
};

const ApplicationCard = ({ application, onStatusChange, onDelete }) => {
    return (
        <div className="relative group">
            <JobCard 
                job={application.job} 
                actionButton={
                    <div className="flex flex-col items-end gap-2">
                        <span className={`px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider ${statusColors[application.status]}`}>
                            {application.status}
                        </span>
                        <div className="flex items-center gap-2 mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <select 
                                value={application.status}
                                onChange={(e) => onStatusChange(application.id, e.target.value)}
                                className="text-xs border-gray-300 rounded p-1"
                            >
                                <option value="saved">Saved</option>
                                <option value="applied">Applied</option>
                                <option value="interview">Interview</option>
                                <option value="offer">Offer</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <button 
                                onClick={() => onDelete(application.id)}
                                className="text-xs text-red-600 hover:text-red-800"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                } 
            />
            {application.notes && (
                <div className="mt-2 text-sm text-gray-500 italic px-6 pb-4">
                    Notes: {application.notes}
                </div>
            )}
        </div>
    );
};

export default ApplicationCard;
