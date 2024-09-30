<?php

namespace App\Listeners;

use App\Events\JobCompleted;
use App\Events\JobFailed;
use App\Models\JobLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class JobEventSubscriber
{
    public function handleJobCompleted(JobCompleted $event)
    {
        JobLog::create([
            'state' => 2,
            'create_ts' => now(),
            'scheduled_for_ts' => now()->addSeconds($event->delay),
        ]);
    }

    public function handleJobFailed(JobFailed $event)
    {
        JobLog::create([
            'state' => 0,
            'create_ts' => now(),
            'scheduled_for_ts' => now()->addSeconds($event->jobDelay),
        ]);
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            JobCompleted::class => 'handleJobCompleted',
            JobFailed::class => 'handleJobFailed',
        ];
    }
}
