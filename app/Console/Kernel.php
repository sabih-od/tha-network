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
//        //testing | suspend and close accounts
        $schedule->call(function () {payment_not_made();})->monthlyOn(20, '00:00');
        $schedule->call(function () {close_accounts();})->monthlyOn(21, '00:00');
//        //testing | suspend and close accounts

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
        $dates = [3, 7, 14];
        foreach ($dates as $date) {
            $schedule->call(function () {payment_not_made();})->monthlyOn($date, '00:00');
        }
//        // suspend accounts
//        $schedule->call(function () {suspend_accounts();})->monthlyOn(7, '00:00');
        // close accounts
        $schedule->call(function () {close_accounts();})->monthlyOn(15, '00:00');
        // commission distribution
//        $schedule->call(function () {commission_distribution();})->monthlyOn(15, '00:00');
        $schedule->call(function () {commission_distribution();})->dailyAt('00:00');

        //close garbage accounts (30 days old | Deleted, Closed, Suspended)
        $schedule->call(function () {
            delete_deleted_accounts();
            delete_closed_accounts();
            delete_suspended_accounts();
        })->monthlyOn(1, '00:00');
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
