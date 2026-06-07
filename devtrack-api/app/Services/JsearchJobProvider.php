<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JsearchJobProvider implements JobServiceInterface
{
    private string $apiKey;
    private string $country;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.jsearch.key', '');
        $this->country = config('services.jsearch.country', 'Philippines');
        $this->baseUrl = config('services.jsearch.base_url', 'https://api.openwebninja.com');
    }

    public function searchJobs(string $query = ''): array
    {
        if (empty($this->apiKey)) {
            Log::warning('JSearch API key not configured.');
            return [];
        }

        $trimmedQuery = trim($query);
        if (empty($trimmedQuery)) {
            $trimmedQuery = 'Developer';
        }

        // Target local PH works by appending location if not specified in search query
        $searchQuery = $trimmedQuery;
        if (!str_contains(strtolower($trimmedQuery), 'philippines') && !str_contains(strtolower($trimmedQuery), 'ph')) {
            $searchQuery .= " in {$this->country}";
        }

        $url = "{$this->baseUrl}/jsearch/search-v2";

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get($url, [
                'query' => $searchQuery,
                'num_pages' => 1,
            ]);

            if ($response->successful()) {
                $jobsData = $response->json('data.jobs', []);
                $jobs = array_map([$this, 'mapJob'], $jobsData);

                // Filter out duplicate jobs by external_job_id
                $uniqueJobs = [];
                foreach ($jobs as $job) {
                    $uniqueJobs[$job['external_job_id']] = $job;
                }
                return array_values($uniqueJobs);
            }

            Log::error('JSearch API error', ['response' => $response->body()]);
            return [];
        } catch (\Exception $e) {
            Log::error('JSearch API exception: ' . $e->getMessage());
            return [];
        }
    }

    public function getJob(string $externalJobId): ?array
    {
        // Details are fetched and saved locally in the database when saved or applied,
        // matching the existing system pattern.
        return null;
    }

    private function mapJob(array $jobData): array
    {
        $id = $jobData['job_id'] ?? uniqid('js_');
        
        $city = $jobData['job_city'] ?? '';
        $state = $jobData['job_state'] ?? '';
        $country = $jobData['job_country'] ?? '';
        
        $locationParts = array_filter([$city, $state, $country]);
        $locationLabel = implode(', ', $locationParts);
        
        if (empty($locationLabel)) {
            $locationLabel = $jobData['job_location'] ?? 'Remote';
        }

        $applyUrl = $jobData['job_apply_link'] ?? null;
        if (empty($applyUrl) && isset($jobData['apply_options'][0]['apply_link'])) {
            $applyUrl = $jobData['apply_options'][0]['apply_link'];
        }

        $createdAt = $jobData['job_posted_at_datetime_utc'] ?? null;
        if (empty($createdAt) && isset($jobData['job_posted_at_timestamp'])) {
            $createdAt = date('c', $jobData['job_posted_at_timestamp']);
        }
        if (empty($createdAt)) {
            $createdAt = now()->toIso8601String();
        }

        return [
            'external_job_id' => (string) $id,
            'title' => $jobData['job_title'] ?? 'Unknown Title',
            'company' => $jobData['employer_name'] ?? 'Unknown Company',
            'location' => $locationLabel,
            'description' => strip_tags($jobData['job_description'] ?? ''),
            'source' => 'JSearch',
            'apply_url' => $applyUrl,
            'created_at' => $createdAt,
        ];
    }
}
