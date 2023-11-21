<?php

namespace App\Console\Commands;

use App\Jobs\SmartRetries;
use App\Jobs\UnableToMeetWeeklyGoal;
use Illuminate\Console\Command;

class UnableToMeetWeeklyGoalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unable_to_meet:weekly_goal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        event(UnableToMeetWeeklyGoal::dispatch());
    }
}
