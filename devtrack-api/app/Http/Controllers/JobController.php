<?php

namespace App\Http\Controllers;

use App\Services\JobServiceInterface;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct(private JobServiceInterface $jobService) {}

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $jobs = $this->jobService->searchJobs($query);

        return response()->json(['data' => $jobs]);
    }

    public function show($id)
    {
        $job = $this->jobService->getJob($id);

        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        return response()->json(['data' => $job]);
    }
}
