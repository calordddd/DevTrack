<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_list_saved_jobs(): void
    {
        $response = $this->getJson('/api/saved-jobs');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_list_saved_jobs(): void
    {
        $user = User::factory()->create();
        $job = Job::create([
            'external_job_id' => 'ext_job_001',
            'title' => 'Software Engineer',
            'company' => 'Google',
        ]);
        $savedJob = SavedJob::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        $response = $this->actingAs($user)->getJson('/api/saved-jobs');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $savedJob->id);
        $response->assertJsonPath('data.0.job.id', $job->id);
    }

    public function test_authenticated_user_can_save_job(): void
    {
        $user = User::factory()->create();
        $jobData = [
            'external_job_id' => 'ext_job_123',
            'title' => 'Software Engineer',
            'company' => 'Google',
            'location' => 'Mountain View, CA',
            'description' => 'Great job!',
            'source' => 'MockSource',
            'apply_url' => 'https://google.com/apply',
        ];

        $response = $this->actingAs($user)->postJson('/api/saved-jobs', [
            'job' => $jobData
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('job_listings', [
            'external_job_id' => 'ext_job_123',
            'title' => 'Software Engineer',
        ]);
        $this->assertDatabaseHas('saved_jobs', [
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_unsave_job(): void
    {
        $user = User::factory()->create();
        $job = Job::create([
            'external_job_id' => 'ext_job_001',
            'title' => 'Software Engineer',
            'company' => 'Google',
        ]);
        $savedJob = SavedJob::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/saved-jobs/{$savedJob->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Job removed from saved list']);
        $this->assertDatabaseMissing('saved_jobs', [
            'id' => $savedJob->id,
        ]);
    }
}
