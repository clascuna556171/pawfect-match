<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Models\Application;
use App\Mail\AdoptionUpdateReminder;

class SendAdoptionUpdateReminder implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        $applications = Application::where('status', 'approved')
            ->whereNotNull('reviewed_at')
            ->whereDate('reviewed_at', now()->subDays(7)->toDateString())
            ->with(['user', 'pet'])
            ->get();

        foreach ($applications as $application) {
            Mail::to($application->user->email)->send(new AdoptionUpdateReminder($application));
        }
    }
}
