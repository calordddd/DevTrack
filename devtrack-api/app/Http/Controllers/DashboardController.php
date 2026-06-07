<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
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
