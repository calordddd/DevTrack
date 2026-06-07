<?php

namespace Tests\Feature;

use App\Services\JsearchJobProvider;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JsearchJobProviderTest extends TestCase
{
    public function test_search_jobs_returns_formatted_data(): void
    {
        Http::fake([
            'api.openwebninja.com/jsearch/search-v2*' => Http::response([
                'status' => 'OK',
                'data' => [
                    'jobs' => [
                        [
                            'job_id' => '12345',
                            'job_title' => 'Software Engineer',
                            'employer_name' => 'Test Company',
                            'job_city' => 'Taguig',
                            'job_state' => 'Metro Manila',
                            'job_country' => 'PH',
                            'job_description' => 'We are hiring a Software Engineer...',
                            'job_apply_link' => 'https://example.com/apply',
                            'job_posted_at_datetime_utc' => '2026-06-08T00:00:00Z',
                        ]
                    ]
                ]
            ], 200)
        ]);

        config(['services.jsearch.key' => 'test-api-key']);
        config(['services.jsearch.country' => 'Philippines']);

        $provider = new JsearchJobProvider();
        $results = $provider->searchJobs('Laravel');

        $this->assertCount(1, $results);
        $this->assertEquals('12345', $results[0]['external_job_id']);
        $this->assertEquals('Software Engineer', $results[0]['title']);
        $this->assertEquals('Test Company', $results[0]['company']);
        $this->assertEquals('Taguig, Metro Manila, PH', $results[0]['location']);
        $this->assertEquals('We are hiring a Software Engineer...', $results[0]['description']);
        $this->assertEquals('JSearch', $results[0]['source']);
        $this->assertEquals('https://example.com/apply', $results[0]['apply_url']);
        $this->assertEquals('2026-06-08T00:00:00Z', $results[0]['created_at']);
    }

    public function test_search_jobs_appends_location_to_query(): void
    {
        Http::fake([
            'api.openwebninja.com/jsearch/search-v2*' => function ($request) {
                $urlParts = parse_url($request->url());
                parse_str($urlParts['query'] ?? '', $queryParams);
                $this->assertEquals('Laravel in Philippines', $queryParams['query'] ?? '');
                
                return Http::response([
                    'status' => 'OK',
                    'data' => ['jobs' => []]
                ], 200);
            }
        ]);

        config(['services.jsearch.key' => 'test-api-key']);
        config(['services.jsearch.country' => 'Philippines']);
        
        $provider = new JsearchJobProvider();
        $provider->searchJobs('Laravel');
    }
}
