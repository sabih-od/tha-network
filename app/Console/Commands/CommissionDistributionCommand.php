<?php

namespace App\Console\Commands;

use App\Jobs\CommissionDistribution;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CommissionDistributionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:distribute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Commission Distribution Job Command';

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
        event(CommissionDistribution::dispatch());
    }
}
