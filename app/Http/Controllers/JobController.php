<?php

namespace App\Http\Controllers;

use App\Models\JobLog;
use App\Repositories\JobLogRepository;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct(private JobLogRepository $jobLogRepository)
    {
        //
    }

    public function list(Request $request)
    {
        $limit = $request->query('limit', 10);

        $jobLogs = $this->jobLogRepository->getJobLogs($limit);

        return response()->json(['data' => $jobLogs]);
    }
}
