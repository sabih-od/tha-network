<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

//        $schedule->call(function (){
//            dump('here');
//        })->everyMinute();

        //last week's rankings
        $schedule->call(function () {last_weeks_rankings();})->weeklyOn(1, '07:00');
        //unable to meet weekly goal
        $schedule->call(function () {unable_to_meet_weekly_goal();})->weeklyOn(7, '23:59');
        //no referrals for the day
        $schedule->call(function () {no_notification_for_the_day();})->dailyAt('23:59');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
