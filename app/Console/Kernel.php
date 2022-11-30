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

        //monthly add goals
        $schedule->call(function () {monthly_add_goals();})->monthlyOn(1, '00:00');
        //set weekly goal
        $schedule->call(function () {set_weekly_goal();})->weeklyOn(1, '00:00');
        //last week's rankings
        $schedule->call(function () {last_weeks_rankings();})->weeklyOn(1, '07:00');
        //unable to meet weekly goal
        $schedule->call(function () {unable_to_meet_weekly_goal();})->weeklyOn(7, '23:59');
        //no referrals for the day
        $schedule->call(function () {no_referrals_for_the_day();})->dailyAt('23:59');
        // payment not made
//        $dates = [2, 4, 6, 8, 10, 12, 14];
        $dates = [2, 7, 14];
        foreach ($dates as $date) {
            $schedule->call(function () {payment_not_made();})->monthlyOn($date, '00:00');
        }
//        // suspend accounts
//        $schedule->call(function () {suspend_accounts();})->monthlyOn(7, '00:00');
        // close accounts
        $schedule->call(function () {close_accounts();})->monthlyOn(15, '00:00');
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
