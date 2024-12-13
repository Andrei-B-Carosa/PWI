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
        // Run Leav credit distribution monthy 
        $schedule->command('app:distribute-leave-credits')->monthlyOn(1, '00:00');

        // Run Increment leave credit yearly
        $schedule->command('app:increment-milestones')->yearlyOn(1, '00:30');

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
