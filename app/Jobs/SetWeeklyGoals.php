<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SetWeeklyGoals implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        NEW WORK EACH WEEk WEEKLY GOAL SHOULD BE RESET TO  25
            $users = \App\Models\User::all();
            foreach ($users as $user){
                $user->remaining_referrals = 25;
                $user->save();
            }


        Log::info('JOB (set_weekly_goal()) at: ' . Carbon::now());
        set_weekly_goal();
    }
}
