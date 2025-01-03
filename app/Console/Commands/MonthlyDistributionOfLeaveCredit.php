<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MonthlyDistributionOfLeaveCredit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monthly-distribution-of-leave-credit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Distribute leave credits monthly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //fiscal_year : 1 = monthly 2 = annual
        $settings = DB::table('hris_leave_settings')->where('credit_type', 1)->where('fiscal_year', 1)->where('status', 1)->get();

        foreach ($settings as $setting) {
            $classificationIds = json_decode($setting->classification_id, true);
            $employmentIds = json_decode($setting->employment_id, true);
            $locationIds = json_decode($setting->location_id, true);

            $users = DB::table('hris_employee_positions')
                ->join('employees', 'hris_employee_positions.emp_id', '=', 'employees.id')
                ->when($classificationIds, fn($query) => $query->whereIn('classification_id', $classificationIds))
                ->when($employmentIds, fn($query) => $query->whereIn('employment_id', $employmentIds))
                ->when($locationIds, fn($query) => $query->whereIn('company_location_id', $locationIds))
                ->where('employees.is_active', 1)
                ->get();

            foreach ($users as $user) {
                DB::table('hris_employee_leave_balances')->updateOrInsert(
                    ['emp_id' => $user->emp_id, 'leave_type_id' => $setting->leave_type_id],
                    ['leave_balance' => DB::raw("leave_balance + {$setting->start_credit}")]
                );
            }
        }

        $this->info('Leave credits distributed successfully.');
    }
}
