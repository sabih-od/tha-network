<?php

namespace App\Console\Commands;

use App\Jobs\NoReferralsForTheDay;
use App\Jobs\SmartRetries;
use Illuminate\Console\Command;

class NoReferralsForTheDayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'no_referrals:for_the_day';

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
        event(NoReferralsForTheDay::dispatch());
    }
}
