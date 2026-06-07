<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedJob;
use App\Models\Job;

class SavedJobController extends Controller
{
    public function index(Request $request)
    {
        $savedJobs = $request->user()->savedJobs()->with('job')->get();
        return response()->json(['data' => $savedJobs]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'job' => 'required|array',
            'job.external_job_id' => 'required|string',
            'job.title' => 'required|string',
            'job.company' => 'required|string',
        ]);

        $jobData = $request->input('job');
        
        $job = Job::firstOrCreate(
            ['external_job_id' => $jobData['external_job_id']],
            [
                'title' => $jobData['title'],
                'company' => $jobData['company'],
                'location' => $jobData['location'] ?? null,
                'description' => $jobData['description'] ?? null,
                'source' => $jobData['source'] ?? null,
                'apply_url' => $jobData['apply_url'] ?? null,
            ]
        );

        $savedJob = SavedJob::firstOrCreate([
            'user_id' => $request->user()->id,
            'job_id' => $job->id,
        ]);

        return response()->json(['data' => $savedJob->load('job')], 201);
    }

    public function destroy(Request $request, $id)
    {
        $savedJob = $request->user()->savedJobs()->findOrFail($id);
        $savedJob->delete();

        return response()->json(['message' => 'Job removed from saved list']);
    }
}
