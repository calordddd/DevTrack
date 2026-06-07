<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdzunaJobProvider implements JobServiceInterface
{
    private string $appId;
    private string $appKey;
    private string $country;

    public function __construct()
    {
        $this->appId = config('services.adzuna.app_id', '');
        $this->appKey = config('services.adzuna.app_key', '');
        $this->country = config('services.adzuna.country', 'us');
    }

    public function searchJobs(string $query = ''): array
    {
        if (empty($this->appId) || empty($this->appKey)) {
            Log::warning('Adzuna API credentials not configured.');
            return [];
        }

        $url = "https://api.adzuna.com/v1/api/jobs/{$this->country}/search/1";
        
        $params = [
            'app_id' => $this->appId,
            'app_key' => $this->appKey,
            'results_per_page' => 20,
        ];

        if (!empty($query)) {
            $params['what'] = $query;
        }

        try {
            $response = Http::get($url, $params);

            if ($response->successful()) {
                $results = $response->json('results', []);
                return array_map([$this, 'mapJob'], $results);
            }
            
            Log::error('Adzuna API error', ['response' => $response->body()]);
            return [];
        } catch (\Exception $e) {
            Log::error('Adzuna API exception: ' . $e->getMessage());
            return [];
        }
    }

    public function getJob(string $externalJobId): ?array
    {
        // For individual job fetching, Adzuna provides a different structure or requires
        // the full URL, but since we save job details locally on "Save", we usually don't 
        // need to refetch it from Adzuna unless specifically requested.
        return null;
    }

    private function mapJob(array $jobData): array
    {
        return [
            'external_job_id' => (string) ($jobData['id'] ?? uniqid('adz_')),
            'title' => $jobData['title'] ?? 'Unknown Title',
            'company' => $jobData['company']['display_name'] ?? 'Unknown Company',
            'location' => $jobData['location']['display_name'] ?? 'Remote',
            'description' => strip_tags($jobData['description'] ?? ''),
            'source' => 'Adzuna',
            'apply_url' => $jobData['redirect_url'] ?? null,
            'created_at' => $jobData['created'] ?? now()->toIso8601String(),
        ];
    }
}
