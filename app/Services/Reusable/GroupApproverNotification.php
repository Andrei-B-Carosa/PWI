<?php
namespace App\Services\Reusable;

use App\Mail\GroupApproverNotificationMail;
use App\Models\EmployeeAccount;
use App\Models\HrisGroupApprover;
use App\Models\HrisGroupApproverNotification;
use App\Models\HrisRole;
use App\Models\HrisRoleAccess;
use App\Models\HrisSystemFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GroupApproverNotification
{
    protected $type;
    public function  __construct()
    {
        $this->type = [
            1 =>[
                'subject'=>'Approval for OT Request',
                'body'=>'is requesting to render overtime from',
                'column_date'=>'overtime_date',
                'column_from'=>'overtime_from',
                'column_to'=>'overtime_to',
            ],
            2 =>[
                'subject'=>'Approval for Leave Request',
                'body'=>'is requesting for your authorization to leave from',
                'column_date'=>'leave_filing_date',
                'column_from'=>'leave_date_from',
                'column_to'=>'leave_date_to',
            ],
            3 =>[
                'subject'=>'Approval for OB Request',
                'body'=>'is requesting for official business from',
                'column_date'=>'ob_filing_date',
                'column_from'=>'ob_time_out',
                'column_to'=>'ob_time_in',
            ],
        ];
    }

    public function sendApprovalNotification($query, $entity_table, $route,$isResubmit)
    {
        $entity_id = $query->id;
        $group_id = $query->group_member->group_id;
        $isNotified = false;

        $approvers = self::getGroupApprovers($entity_id, $entity_table, $group_id);
        foreach ($approvers as $key => $approverRecord) {
            $approver = EmployeeAccount::where([['emp_id', $approverRecord->emp_id],['is_active', 1]])->first();
            if (!$approver || !$approver->c_email) {
                $isNotified = false;
                break;
            }

            // Check if this approver has already been notified in this session
            $alreadyNotified = HrisGroupApproverNotification::where([
                ['entity_id', $entity_id],
                ['entity_table', $entity_table],
                ['group_id', $group_id],
                ['emp_id', $approverRecord->emp_id],
            ])->where(function ($query) {
                $query->where('is_approved', 1)
                      ->orWhere('is_approved', null);
            })->latest()->first();

            if ($alreadyNotified) {
                //stop loop if the notified approver is required and their approval is pending
                if($alreadyNotified->is_required){
                    $isNotified = true;
                    break;
                }
                continue;
            }

            // Prepare notification data
            [$link,$token] = self::generateLink($query, $approver, $route);
            $data = $this->prepareNotificationData($query, $entity_table, $approver,$link,$isResubmit);
            try {
                Mail::to($approver->c_email)->send(new GroupApproverNotificationMail($data));
                $isNotified = true; // Successfully notified
            } catch (\Exception $e) {
                Log::error("Email failed to send to approver {$approver->employee->fullname()}: " . $e->getMessage());
                $isNotified = false;
                break; // Exit on email failure
            }

            // // Log the notification
            if($isNotified){
                HrisGroupApproverNotification::create([
                    'entity_id' => $entity_id,
                    'entity_table' => $entity_table,
                    'group_id' => $group_id,
                    'request_link_token'=>$token,
                    'link_status' =>1,
                    'link_expired_at'=>Carbon::now()->addDays(15),
                    'emp_id' => $approverRecord->emp_id,
                    'approver_level' => $approverRecord->approver_level,
                    'is_final_approver' => $approverRecord->is_final_approver,
                    'is_required' =>$approverRecord->is_required,
                ]);
            }

            // Stop if current approver requires approval
            if ($approverRecord->is_required) {
                break;
            }
        }
        return $isNotified;
    }

    public function getGroupApprovers($entity_id,$entity_table,$group_id)
    {
        return HrisGroupApprover::where([['is_active', 1], ['group_id', $group_id]])
        ->whereDoesntHave('approving_history', function ($query) use ($entity_id,$entity_table) {
            $query->where([
                ['entity_id', $entity_id],
                ['entity_table', $entity_table], // Adjust entity_table value accordingly
                ['is_approved', 1], // Exclude those who have already approved
            ]);
        })
        ->orderBy('is_final_approver', 'ASC')
        ->orderBy('approver_level', 'DESC')
        ->get();
    }

    public function generateLink($query,$approver,$route)
    {
        do {
            $uniqueToken = Str::random(32);
        } while (HrisGroupApproverNotification::where('request_link_token', $uniqueToken)->exists());

        $routeData = ['token'=>$uniqueToken];
        return [route($route, $routeData),$uniqueToken];
    }

    private function prepareNotificationData($query, $entity_table, $approver, $link,$isResubmit)
    {
        return [
            'subject' => $this->type[$entity_table]['subject'],
            'message' => "{$query->employee->fullname()}
                        {$this->type[$entity_table]['body']}
                        {$query->{$this->type[$entity_table]['column_date']}}
                        {$query->{$this->type[$entity_table]['column_from']}}
                        to {$query->{$this->type[$entity_table]['column_to']}}.",
            'link' => $link,
            'isResubmit'=>$isResubmit,
        ];
    }
}

?>
