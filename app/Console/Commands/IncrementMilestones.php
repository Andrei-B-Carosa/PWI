<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class IncrementMilestones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:increment-milestones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increment leave credits based on milestones for succeeding years';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $countIncrement = 0;
        $today = now();

        // Fetch leave settings with succeeding_year set to increment
        $settings = DB::table('hris_leave_settings')
            ->where('succeeding_year', 2) // Increment
            ->where('status',1)
            ->get();

        foreach ($settings as $setting) {

            // Fetch employees matching the classification, employment, and location IDs
            $classificationIds = json_decode($setting->classification_id, true);
            $employmentIds = json_decode($setting->employment_id, true);
            $locationIds = json_decode($setting->location_id, true);
            $milestones = json_decode($setting->increment_milestones, true);

            if (!$milestones) {
                $this->warn("No increment milestones defined for leave type {$setting->leave_type_id}");
                continue;
            }

            $employees = DB::table('hris_employee_positions')
                ->join('employees', 'hris_employee_positions.emp_id', '=', 'employees.id')
                ->when($classificationIds, fn($query) => $query->whereIn('classification_id', $classificationIds))
                ->when($employmentIds, fn($query) => $query->whereIn('employment_id', $employmentIds))
                ->when($locationIds, fn($query) => $query->whereIn('company_location_id', $locationIds))
                ->where('employees.is_active', 1)
                ->get();

            foreach ($employees as $employee) {
                // Calculate years of service
                $yearsOfService = $today->diffInYears($employee->date_hired);

                // Find the applicable milestone for the current years of service
                $applicableMilestone = collect($milestones)
                    ->where('year', '<=', $yearsOfService) // Only consider past milestones
                    ->sortByDesc('year') // Sort to get the highest applicable milestone
                    ->first();

                if ($applicableMilestone) {
                    $additionalCredit = $applicableMilestone['credit'];

                    // Update leave balance
                    DB::table('hris_employee_leave_balances')->updateOrInsert(
                        ['emp_id' => $employee->emp_id, 'leave_type_id' => $setting->leave_type_id],
                        ['leave_balance' => DB::raw("leave_balance + $additionalCredit")]
                    );

                    $countIncrement++;
                }
            }
        }

        $this->info('Milestone increments processed successfully. Incremeted:'.$countIncrement);
    }
}
