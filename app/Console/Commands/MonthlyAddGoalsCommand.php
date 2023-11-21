<?php

namespace App\Console\Commands;

use App\Jobs\MonthlyAddGoals;
use App\Jobs\SmartRetries;
use Illuminate\Console\Command;

class MonthlyAddGoalsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly:add_goals';

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
        event(MonthlyAddGoals::dispatch());
    }
}
