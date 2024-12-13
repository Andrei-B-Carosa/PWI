<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetLeaveCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-leave-credits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now();
        $countResetted = 0;
        $settings = DB::table('hris_leave_settings')->where([['is_reset', 1],['status',1]])->get();

        foreach ($settings as $setting) {
            $resetDate = now()->setMonth($setting->reset_month)->setDay($setting->reset_day);

            if ($today->isSameDay($resetDate)) {
                $classificationIds = json_decode($setting->classification_id, true);
                $employmentIds = json_decode($setting->employment_id, true);
                $locationIds = json_decode($setting->location_id, true);

                $employees = DB::table('hris_employee_positions')
                    ->when($classificationIds, fn($query) => $query->whereIn('classification_id', $classificationIds))
                    ->when($employmentIds, fn($query) => $query->whereIn('employment_id', $employmentIds))
                    ->when($locationIds, fn($query) => $query->whereIn('company_location_id', $locationIds))
                    ->get();

                foreach ($employees as $employee) {
                    DB::table('hris_employee_leave_balances')->where('emp_id', $employee->emp_id)->update(['leave_balance' => 0]);
                    $countResetted++;
                }
            }
        }

        $this->info('Leave balances reset successfully. Resetted:'.$countResetted);
    }
}
