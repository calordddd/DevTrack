<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = $request->user()->applications()->with('job')->get();
        return response()->json(['data' => $applications]);
    }

    public function store(StoreApplicationRequest $request)
    {
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

        $application = Application::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'job_id' => $job->id,
            ],
            [
                'status' => $request->input('status', 'applied'),
                'notes' => $request->input('notes'),
            ]
        );

        return response()->json(['data' => $application->load('job')], 201);
    }

    public function update(UpdateApplicationRequest $request, $id)
    {
        $application = $request->user()->applications()->findOrFail($id);
        $application->update($request->validated());

        return response()->json(['data' => $application->load('job')]);
    }

    public function destroy(Request $request, $id)
    {
        $application = $request->user()->applications()->findOrFail($id);
        $application->delete();

        return response()->json(['message' => 'Application deleted']);
    }
}
