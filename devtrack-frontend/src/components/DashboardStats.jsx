import React from 'react';

const DashboardStats = ({ stats }) => {
    const statCards = [
        { title: 'Total Applications', value: stats.total_applications, color: 'text-blue-600' },
        { title: 'Interviews', value: stats.interviews, color: 'text-purple-600' },
        { title: 'Offers', value: stats.offers, color: 'text-green-600' },
        { title: 'Rejections', value: stats.rejections, color: 'text-red-600' },
        { title: 'Saved Jobs', value: stats.saved_jobs, color: 'text-yellow-600' },
    ];

    return (
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
            {statCards.map((stat, index) => (
                <div key={index} className="bg-surface rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col items-center justify-center transition-transform hover:-translate-y-1">
                    <div className="text-gray-500 text-sm font-medium mb-2">{stat.title}</div>
                    <div className={`text-3xl font-bold ${stat.color}`}>
                        {stat.value}
                    </div>
                </div>
            ))}
        </div>
    );
};

export default DashboardStats;
