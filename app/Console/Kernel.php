<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

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
        //testing | suspend and close accounts | created on 12
//        $dates = [14, 15];
//        foreach ($dates as $date) {
//            $schedule->call(function () {payment_not_made();})->monthlyOn($date, '00:00');
//        }
//        $schedule->call(function () {payment_not_made();})->dailyAt('00:30');
//        $schedule->call(function () {commission_distribution();})->monthlyOn(17, '16:00')->timezone('UTC')->withoutOverlapping();
//        $schedule->call(function () {np_email();})->monthlyOn(17, '16:10')->timezone('UTC')->withoutOverlapping();
//
//        $schedule->call(function () {smart_retries();})->monthlyOn(18, '16:00')->timezone('UTC')->withoutOverlapping();
//        $schedule->call(function () {np_email();})->monthlyOn(18, '16:10')->timezone('UTC')->withoutOverlapping();
//
//        $schedule->call(function () {smart_retries();})->monthlyOn(19, '16:00')->timezone('UTC')->withoutOverlapping();
//        $schedule->call(function () {close_accounts();})->monthlyOn(19, '16:10')->timezone('UTC')->withoutOverlapping();
//
//        $schedule->call(function () {commission_distribution();})->monthlyOn(20, '16:00')->timezone('UTC')->withoutOverlapping();
//        $schedule->call(function () {np_email();})->monthlyOn(20, '16:10')->timezone('UTC')->withoutOverlapping();
//
//        $schedule->call(function () {smart_retries();})->monthlyOn(21, '16:00')->timezone('UTC')->withoutOverlapping();
//        $schedule->call(function () {np_email();})->monthlyOn(21, '16:10')->timezone('UTC')->withoutOverlapping();
//
//        $schedule->call(function () {smart_retries();})->monthlyOn(22, '16:00')->timezone('UTC')->withoutOverlapping();
//        $schedule->call(function () {close_accounts();})->monthlyOn(22, '16:10')->timezone('UTC')->withoutOverlapping();

//        //23
//        $schedule->call(function () {commission_distribution();})->monthlyOn(24, '00:00')->timezone('UTC')->name('commission_distribution')->withoutOverlapping();
//        $schedule->call(function () {np_email();})->monthlyOn(24, '00:10')->timezone('UTC')->name('np_email')->withoutOverlapping();
//
//        //24
//        $schedule->call(function () {smart_retries();})->monthlyOn(25, '00:00')->timezone('UTC')->name('smart_retries')->withoutOverlapping();
//        $schedule->call(function () {np_email();})->monthlyOn(25, '00:10')->timezone('UTC')->name('np_email')->withoutOverlapping();
//
//        //25
//        $schedule->call(function () {smart_retries();})->monthlyOn(26, '00:00')->timezone('UTC')->name('smart_retries')->withoutOverlapping();
//        $schedule->call(function () {close_accounts();})->monthlyOn(26, '00:10')->timezone('UTC')->name('close_accounts')->withoutOverlapping();
//
//        //26
//        $schedule->call(function () {commission_distribution();})->monthlyOn(27, '00:00')->timezone('UTC')->name('commission_distribution')->withoutOverlapping();
//        $schedule->call(function () {np_email();})->monthlyOn(27, '00:10')->timezone('UTC')->name('np_email')->withoutOverlapping();
//
//        //27
//        $schedule->call(function () {smart_retries();})->monthlyOn(28, '00:00')->timezone('UTC')->name('smart_retries')->withoutOverlapping();
//        $schedule->call(function () {np_email();})->monthlyOn(28, '00:10')->timezone('UTC')->name('np_email')->withoutOverlapping();
//
//        //28
//        $schedule->call(function () {smart_retries();})->monthlyOn(29, '00:00')->timezone('UTC')->name('smart_retries')->withoutOverlapping();
//        $schedule->call(function () {close_accounts();})->monthlyOn(29, '00:10')->timezone('UTC')->name('close_accounts')->withoutOverlapping();

//        $dates = [15, 16, 17, 18, 19, 20];
//        foreach ($dates as $date) {
//            $schedule->call(function () {commission_distribution();})->monthlyOn($date, '00:00');
//            $schedule->call(function () {smart_retries();})->monthlyOn($date, '00:00');
//        }
//        $schedule->call(function () {close_accounts();})->everyThirtyMinutes();
        //testing | suspend and close accounts

//        //monthly add goals
//        $schedule->call(function () {monthly_add_goals();})->monthlyOn(1, '00:00');
//        //set weekly goal
//        $schedule->call(function () {set_weekly_goal();})->weeklyOn(1, '00:00');
//        //last week's rankings
//        $schedule->call(function () {last_weeks_rankings();})->weeklyOn(1, '07:00');
//        //unable to meet weekly goal
//        $schedule->call(function () {unable_to_meet_weekly_goal();})->weeklyOn(7, '23:59');
//        //no referrals for the day
////        $schedule->call(function () {no_referrals_for_the_day();})->dailyAt('23:59');
//        $schedule->call(function () {no_referrals_for_the_day();})->weeklyOn(1, '23:59');
//        // payment not made
//        $dates = [3, 7, 14];
//        foreach ($dates as $date) {
//            $schedule->call(function () {payment_not_made();})->monthlyOn($date, '00:00');
//        }
////        // suspend accounts
////        $schedule->call(function () {suspend_accounts();})->monthlyOn(7, '00:00');
//        // close accounts
//        $schedule->call(function () {close_accounts();})->monthlyOn(15, '00:00');
//        // commission distribution
////        $schedule->call(function () {commission_distribution();})->monthlyOn(15, '00:00');
//        $schedule->call(function () {commission_distribution();})->dailyAt('00:00');
//
//        //close garbage accounts (30 days old | Deleted, Closed, Suspended)
//        $schedule->call(function () {
//            delete_deleted_accounts();
//            delete_closed_accounts();
//            delete_suspended_accounts();
//        })->monthlyOn(1, '00:00');
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
