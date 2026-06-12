<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_list_applications(): void
    {
        $response = $this->getJson('/api/applications');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_list_applications(): void
    {
        $user = User::factory()->create();
        $job = Job::create([
            'external_job_id' => 'ext_job_001',
            'title' => 'Software Engineer',
            'company' => 'Google',
        ]);
        $application = Application::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'status' => 'applied',
            'notes' => 'Applied via website',
        ]);

        $response = $this->actingAs($user)->getJson('/api/applications');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $application->id);
        $response->assertJsonPath('data.0.job.id', $job->id);
    }

    public function test_authenticated_user_can_track_application(): void
    {
        $user = User::factory()->create();
        $jobData = [
            'external_job_id' => 'ext_job_123',
            'title' => 'Backend Developer',
            'company' => 'Meta',
            'location' => 'Menlo Park, CA',
        ];

        $response = $this->actingAs($user)->postJson('/api/applications', [
            'job' => $jobData,
            'status' => 'applied',
            'notes' => 'Referred by employee'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('job_listings', [
            'external_job_id' => 'ext_job_123',
            'title' => 'Backend Developer',
        ]);
        $this->assertDatabaseHas('applications', [
            'user_id' => $user->id,
            'status' => 'applied',
            'notes' => 'Referred by employee'
        ]);
    }

    public function test_authenticated_user_can_update_application(): void
    {
        $user = User::factory()->create();
        $job = Job::create([
            'external_job_id' => 'ext_job_001',
            'title' => 'Frontend Developer',
            'company' => 'Apple',
        ]);
        $application = Application::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'status' => 'applied',
            'notes' => 'First step',
        ]);

        $response = $this->actingAs($user)->putJson("/api/applications/{$application->id}", [
            'status' => 'interview',
            'notes' => 'Scheduled technical round'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'interview',
            'notes' => 'Scheduled technical round'
        ]);
    }

    public function test_authenticated_user_can_delete_application(): void
    {
        $user = User::factory()->create();
        $job = Job::create([
            'external_job_id' => 'ext_job_001',
            'title' => 'DevOps Engineer',
            'company' => 'Amazon',
        ]);
        $application = Application::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'status' => 'applied',
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/applications/{$application->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Application deleted']);
        $this->assertDatabaseMissing('applications', [
            'id' => $application->id,
        ]);
    }
}
