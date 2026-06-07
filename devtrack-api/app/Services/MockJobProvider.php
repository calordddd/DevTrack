<?php

namespace App\Services;

class MockJobProvider implements JobServiceInterface
{
    private array $jobs;

    public function __construct()
    {
        // Mocking 20 realistic jobs
        $this->jobs = [
            [
                'external_job_id' => 'mock-1',
                'title' => 'Frontend Developer (React)',
                'company' => 'TechNova',
                'location' => 'Remote',
                'description' => 'We are looking for an experienced React developer to build modern web applications using React 19, Vite, and Tailwind CSS. The ideal candidate has 3+ years of experience.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/1',
                'created_at' => now()->subDays(1)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-2',
                'title' => 'Backend Engineer (Laravel)',
                'company' => 'SaaS Cloud',
                'location' => 'New York, NY',
                'description' => 'Join our backend team to build robust APIs using Laravel 12. Experience with MySQL, Redis, and RESTful principles is required.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/2',
                'created_at' => now()->subDays(2)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-3',
                'title' => 'Full Stack Developer',
                'company' => 'Startup Inc',
                'location' => 'San Francisco, CA',
                'description' => 'Looking for a full stack engineer proficient in PHP and JavaScript to maintain our core product.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/3',
                'created_at' => now()->subDays(3)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-4',
                'title' => 'Software Engineer II',
                'company' => 'Global Solutions',
                'location' => 'London, UK',
                'description' => 'Develop and maintain scalable enterprise systems.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/4',
                'created_at' => now()->subDays(1)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-5',
                'title' => 'Senior Frontend Engineer',
                'company' => 'Creative Agency',
                'location' => 'Remote',
                'description' => 'Lead frontend projects using modern JS frameworks and UI libraries.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/5',
                'created_at' => now()->subDays(4)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-6',
                'title' => 'DevOps Engineer',
                'company' => 'CloudNet',
                'location' => 'Austin, TX',
                'description' => 'Manage infrastructure, CI/CD pipelines, and AWS deployments.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/6',
                'created_at' => now()->subHours(12)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-7',
                'title' => 'Product Manager',
                'company' => 'Innovate LLC',
                'location' => 'Chicago, IL',
                'description' => 'Drive product strategy and work closely with engineering teams.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/7',
                'created_at' => now()->subDays(2)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-8',
                'title' => 'Data Scientist',
                'company' => 'DataCorp',
                'location' => 'Boston, MA',
                'description' => 'Analyze large datasets using Python and machine learning algorithms.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/8',
                'created_at' => now()->subDays(5)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-9',
                'title' => 'QA Engineer',
                'company' => 'Testify',
                'location' => 'Remote',
                'description' => 'Write automated tests and ensure software quality before release.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/9',
                'created_at' => now()->subDays(1)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-10',
                'title' => 'UI/UX Designer',
                'company' => 'Design Studio',
                'location' => 'Los Angeles, CA',
                'description' => 'Create user-centric designs and prototypes using Figma.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/10',
                'created_at' => now()->subDays(3)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-11',
                'title' => 'Backend Developer (Node.js)',
                'company' => 'ApiSystems',
                'location' => 'Seattle, WA',
                'description' => 'Build high-performance microservices using Node.js and Express.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/11',
                'created_at' => now()->subDays(6)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-12',
                'title' => 'Systems Administrator',
                'company' => 'Enterprise IT',
                'location' => 'Denver, CO',
                'description' => 'Maintain corporate network infrastructure and internal servers.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/12',
                'created_at' => now()->subDays(2)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-13',
                'title' => 'Technical Writer',
                'company' => 'Docs Co',
                'location' => 'Remote',
                'description' => 'Create clear and comprehensive technical documentation for our APIs.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/13',
                'created_at' => now()->subDays(7)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-14',
                'title' => 'Security Engineer',
                'company' => 'SecureNet',
                'location' => 'Washington D.C.',
                'description' => 'Ensure our applications are secure by performing penetration testing and code reviews.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/14',
                'created_at' => now()->subDays(1)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-15',
                'title' => 'React Native Developer',
                'company' => 'Mobile First',
                'location' => 'Remote',
                'description' => 'Build cross-platform mobile apps using React Native.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/15',
                'created_at' => now()->subDays(4)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-16',
                'title' => 'Machine Learning Engineer',
                'company' => 'AI Innovators',
                'location' => 'Toronto, ON',
                'description' => 'Develop and deploy ML models into production.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/16',
                'created_at' => now()->subDays(2)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-17',
                'title' => 'Database Administrator',
                'company' => 'Data Bank',
                'location' => 'Atlanta, GA',
                'description' => 'Manage and optimize large-scale PostgreSQL and MySQL databases.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/17',
                'created_at' => now()->subDays(5)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-18',
                'title' => 'Scrum Master',
                'company' => 'Agile Ways',
                'location' => 'Remote',
                'description' => 'Facilitate agile ceremonies and support the development team.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/18',
                'created_at' => now()->subDays(3)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-19',
                'title' => 'IT Support Specialist',
                'company' => 'HelpDesk Ltd',
                'location' => 'Miami, FL',
                'description' => 'Provide tier 1 and tier 2 technical support to employees.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/19',
                'created_at' => now()->subDays(1)->toIso8601String(),
            ],
            [
                'external_job_id' => 'mock-20',
                'title' => 'Principal Software Engineer',
                'company' => 'Future Tech',
                'location' => 'Seattle, WA',
                'description' => 'Lead technical architecture and mentor junior engineers.',
                'source' => 'MockAPI',
                'apply_url' => 'https://example.com/apply/20',
                'created_at' => now()->subDays(8)->toIso8601String(),
            ],
        ];
    }

    public function searchJobs(string $query = ''): array
    {
        if (empty(trim($query))) {
            return $this->jobs;
        }

        $query = strtolower($query);

        return array_values(array_filter($this->jobs, function ($job) use ($query) {
            return str_contains(strtolower($job['title']), $query) ||
                   str_contains(strtolower($job['company']), $query) ||
                   str_contains(strtolower($job['location']), $query);
        }));
    }

    public function getJob(string $externalJobId): ?array
    {
        foreach ($this->jobs as $job) {
            if ($job['external_job_id'] === $externalJobId) {
                return $job;
            }
        }
        return null;
    }
}
