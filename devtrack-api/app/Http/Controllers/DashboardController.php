<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Retrieve dashboard stats for the authenticated user.
     *
     * Returns counts of total applications, interviews, offers, rejections,
     * and saved jobs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_applications' => $user->applications()->count(),
            'interviews' => $user->applications()->where('status', 'interview')->count(),
            'offers' => $user->applications()->where('status', 'offer')->count(),
            'rejections' => $user->applications()->where('status', 'rejected')->count(),
            'saved_jobs' => $user->savedJobs()->count(),
        ];

        return response()->json(['data' => $stats]);
    }
}
