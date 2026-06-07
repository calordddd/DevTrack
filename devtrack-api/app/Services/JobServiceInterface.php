<?php

namespace App\Services;

interface JobServiceInterface
{
    /**
     * Search for jobs based on a query keyword.
     */
    public function searchJobs(string $query = ''): array;

    /**
     * Get details of a specific job.
     */
    public function getJob(string $externalJobId): ?array;
}
