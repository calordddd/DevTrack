<?php

namespace App\Http\Controllers;

use App\Services\JobServiceInterface;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\JobServiceInterface  $jobService
     */
    public function __construct(private JobServiceInterface $jobService) {}

    /**
     * Search for jobs based on query keywords.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = (string) ($request->input('q') ?? '');
        $jobs = $this->jobService->searchJobs($query);

        return response()->json(['data' => $jobs]);
    }

    /**
     * Retrieve details for a specific job listing.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $job = $this->jobService->getJob($id);

        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        return response()->json(['data' => $job]);
    }
}
