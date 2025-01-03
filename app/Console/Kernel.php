<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run monthly-distribution-of-leave-credit 
        $schedule->command('app:monthly-distribution-of-leave-credit')->monthlyOn(1, '00:00');

        // Run annual-distribution-of-leave-credit
        $schedule->command('app:annual-distribution-of-leave-credit')->yearlyOn(1, '00:30');

        // Run Increment leave credit yearly
        $schedule->command('app:increment-milestones')->yearlyOn(1, '00:50');

        // Run ResetLeaveCredits on December 13 every year
        $schedule->command('app:reset-leave-credits')->yearlyOn(12, 13, '00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');


    }
}
