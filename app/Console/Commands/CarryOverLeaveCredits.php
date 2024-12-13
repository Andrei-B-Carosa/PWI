<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CarryOverLeaveCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:carry-over-leave-credits';

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
        $settings = DB::table('hris_leave_settings')->where('is_carry_over', 1)->where('status',1)->get();

        foreach ($settings as $setting) {
            $carryOverDate = now()->setMonth($setting->carry_over_month)->setDay($setting->carry_over_day);

            if ($today->isSameDay($carryOverDate)) {
                $classificationIds = json_decode($setting->classification_id, true);
                $employmentIds = json_decode($setting->employment_id, true);
                $locationIds = json_decode($setting->location_id, true);

                $employees = DB::table('hris_employee_positions')
                    ->when($classificationIds, fn($query) => $query->whereIn('classification_id', $classificationIds))
                    ->when($employmentIds, fn($query) => $query->whereIn('employment_id', $employmentIds))
                    ->when($locationIds, fn($query) => $query->whereIn('company_location_id', $locationIds))
                    ->get();

                foreach ($employees as $employee) {
                    $unusedBalance = DB::table('hris_employee_leave_balances')
                        ->where('emp_id', $employee->emp_id)
                        ->where('leave_type_id', $setting->leave_type_id)
                        ->value('leave_balance');

                    if ($unusedBalance > 0) {
                        DB::table('hris_employee_leave_balances')->updateOrInsert(
                            ['emp_id' => $employee->emp_id, 'leave_type_id' => $setting->leave_type_id],
                            ['leave_balance' => DB::raw("leave_balance + {$unusedBalance}")]
                        );
                    }
                }
            }
        }

        $this->info('Leave balances carried over successfully.');
    }
}
