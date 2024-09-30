<?php

namespace App\Repositories;

use App\Models\JobLog;

class JobLogRepository
{
    public function getJobLogs(int $limit = 10): array
    {
        $jobs = JobLog::query()->limit($limit)->get();

        return $jobs->map(function ($job) {
            return [
                'createTs' => $job->create_ts->timestamp,
                'scheduledForTs' => $job->scheduled_for_ts ? $job->scheduled_for_ts->timestamp : null,
                'state' => $job->state,
            ];
        })->toArray();
    }
}
