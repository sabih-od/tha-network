<?php

namespace App\Console\Commands;

use App\Jobs\PostDelete60Days;
use Illuminate\Console\Command;

class PostDeleteAfter60DaysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post:delete-after-60-days';

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
        event(PostDelete60Days::dispatch());
    }
}
