<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DistributeLeaveCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:distribute-leave-credits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Distribute leave credits monthly based on HRIS leave settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = DB::table('hris_leave_settings')->where('credit_type', 1)->where('fiscal_year', 1)->get();

        foreach ($settings as $setting) {
            $classificationIds = json_decode($setting->classification_id, true);
            $employmentIds = json_decode($setting->employment_id, true);
            $locationIds = json_decode($setting->location_id, true);

            $users = DB::table('hris_employee_positions')
                ->when($classificationIds, fn($query) => $query->whereIn('classification_id', $classificationIds))
                ->when($employmentIds, fn($query) => $query->whereIn('employment_id', $employmentIds))
                ->when($locationIds, fn($query) => $query->whereIn('company_location_id', $locationIds))
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
