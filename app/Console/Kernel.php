<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\QueryChannelOrderStatus;
use App\Console\Commands\CheckFailedWebhookNotifier;
use App\Console\Commands\QueryChannelRefundStatus;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        QueryChannelOrderStatus::class,
        CheckFailedWebhookNotifier::class,
        QueryChannelRefundStatus::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:query-channel-order-status')
                ->onOneServer()
                ->withoutOverlapping()
                ->hourly();
        $schedule->command('command:check-failed-webhook-notifier')
                ->onOneServer()
                ->withoutOverlapping()
                ->everyFiveMinutes();
        $schedule->command('command:query-channel-refund-status')
                ->onOneServer()
                ->withoutOverlapping()
                ->hourly();
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
