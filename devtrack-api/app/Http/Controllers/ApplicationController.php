<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Retrieve all tracked job applications for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $applications = $request->user()->applications()->with('job')->get();
        return response()->json(['data' => $applications]);
    }

    /**
     * Track a new job application.
     *
     * Creates the job record if it does not already exist.
     *
     * @param  \App\Http\Requests\StoreApplicationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Update application status or notes.
     *
     * @param  \App\Http\Requests\UpdateApplicationRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateApplicationRequest $request, $id)
    {
        $application = $request->user()->applications()->findOrFail($id);
        $application->update($request->validated());

        return response()->json(['data' => $application->load('job')]);
    }

    /**
     * Remove a tracked application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $application = $request->user()->applications()->findOrFail($id);
        $application->delete();

        return response()->json(['message' => 'Application deleted']);
    }

}
