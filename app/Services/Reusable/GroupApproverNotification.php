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
            ],
            2 =>[
                'subject'=>'Approval for Leave Request',
            ],
            3 =>[
                'subject'=>'Approval for OB Request',
            ],
        ];
    }

    public function sendApprovalNotification($query, $entity_table, $route,$isResubmit=false)
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
            $data = $this->prepareNotificationData($query, $entity_table, $approver,$link,$isResubmit,$approverRecord);
            if($data===false){ return $data; }
            try {
                Mail::to($approver->c_email)->later(now()->addMinute(), new GroupApproverNotificationMail($data));
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

    public function prepareNotificationData($query, $entity_table, $approver, $link,$isResubmit,$approverRecord)
    {
        $message =  match($entity_table){
            1=>self::prepareMessageOvertime($query,$isResubmit),
            2=>self::prepareMessageLeave($query,$isResubmit),
            3=>self::prepareMessagOfficialBusiness($query,$isResubmit),
            default=>false,
        };

        if($message === false){   return $message; }

        return [
            'approver'=> $approverRecord->employee->fullname(),
            'approver_level'=> $approverRecord->approver_level,
            'is_final_approver'=> $approverRecord->is_final_approver,
            'is_required'=> $approverRecord->is_required,
            'subject' => $this->type[$entity_table]['subject'],
            'message' => $message,
            'link' => $link,
            'isResubmit'=>$isResubmit,
        ];
    }

    public function prepareMessageOvertime($query,$isResubmit)
    {
        $isResubmit = $isResubmit?'This request is resubmitted':'';
        $html = '
        <p style="margin-bottom:20px; color:#5E6278">
            '.$query->employee->fullname().' is requesting for your authorization to render overtime, below are the details.'.$isResubmit.'
        </p>
        <div style="margin-bottom: 10px">
            <div style="padding-bottom:9px">
                <div style="display:flex; justify-content: space-between; color:#5E6278; font-size: 14px; font-weight:500; margin-bottom:8px">
                    <div style="font-family:Arial,Helvetica,sans-serif; font-weight:bold">OT Filing Date</div>
                    <div style="font-family:Arial,Helvetica,sans-serif"> '.Carbon::parse($query->overtime_date)->format('F d, Y').'</div>
                </div>
                <div style="display:flex; justify-content: space-between; color:#5E6278; font-size: 14px; font-weight:500;margin-bottom:8px">
                    <div style="font-family:Arial,Helvetica,sans-serif; font-weight:bold">From - To</div>
                    <div style="font-family:Arial,Helvetica,sans-serif">'.Carbon::parse($query->overtime_from)->format('h:ia').' to '.Carbon::parse($query->overtime_to)->format('h:ia').'</div>
                </div>
                <div style="justify-content: space-between; color:#5E6278; font-size: 14px; font-weight:500; margin-bottom:8px">
                    <div style="font-family:Arial,Helvetica,sans-serif; font-weight:bold">Reason</div>
                    <div style="font-family:Arial,Helvetica,sans-serif">'.$query->reason.'</div>
                </div>
                <div class="separator separator-dashed" style="margin:15px 0"></div>
            </div>
        </div>';

        return $html;
    }

    public function prepareMessageLeave($query,$isResubmit)
    {
        $isResubmit = $isResubmit?'This request is resubmitted':'';
        $html = '
        <p style="margin-bottom:20px; color:#5E6278">
            '.$query->employee->fullname().' is requesting for your authorization to leave, below are the details. '.$isResubmit.'
        </p>
        <div style="margin-bottom: 10px">
            <div style="padding-bottom:9px">
                <div style="display:flex; justify-content: space-between; color:#5E6278; font-size: 14px; font-weight:500; margin-bottom:8px">
                    <div style="font-family:Arial,Helvetica,sans-serif; font-weight:bold">Leave Filing Date</div>
                    <div style="font-family:Arial,Helvetica,sans-serif"> '.Carbon::parse($query->leave_filing_date)->format('F d, Y').'</div>
                </div>
                <div style="display:flex; justify-content: space-between; color:#5E6278; font-size: 14px; font-weight:500;margin-bottom:8px">
                    <div style="font-family:Arial,Helvetica,sans-serif; font-weight:bold">From - To</div>
                    <div style="font-family:Arial,Helvetica,sans-serif">'.Carbon::parse($query->date_from)->format('F d, Y').' to '.Carbon::parse($query->date_to)->format('F d, Y').'</div>
                </div>
                <div style="justify-content: space-between; color:#5E6278; font-size: 14px; font-weight:500; margin-bottom:8px">
                    <div style="font-family:Arial,Helvetica,sans-serif; font-weight:bold">Reason</div>
                    <div style="font-family:Arial,Helvetica,sans-serif">'.$query->reason.'</div>
                </div>
                <div class="separator separator-dashed" style="margin:15px 0"></div>
            </div>
        </div>';
        return $html;
    }

    public function prepareMessagOfficialBusiness($query,$isResubmit)
    {
        $isResubmit = $isResubmit?'This request is resubmitted':'';
        $html = '
        <p style="margin-bottom:20px; color:#5E6278">
            '.$query->employee->fullname().' is requesting for official business, below are the details.'.$isResubmit.'
        </p>
        <div style="margin-bottom: 10px">
            <div style="padding-bottom:9px">
                <div style="display:flex; justify-content: space-between; color:#5E6278; font-size: 14px; font-weight:500; margin-bottom:8px">
                    <div style="font-family:Arial,Helvetica,sans-serif; font-weight:bold">OB Filing Date</div>
                    <div style="font-family:Arial,Helvetica,sans-serif">'.Carbon::parse($query->ob_filing_date)->format('F d, Y').'</div>
                </div>
                <div style="display:flex; justify-content: space-between; color:#5E6278; font-size: 14px; font-weight:500;margin-bottom:8px">
                    <div style="font-family:Arial,Helvetica,sans-serif; font-weight:bold">Time Out - Time In</div>
                    <div style="font-family:Arial,Helvetica,sans-serif">'.Carbon::parse($query->ob_time_out)->format('h:ia').' to '.Carbon::parse($query->ob_time_in)->format('h:ia').'</div>
                </div>
                <div style="display:flex; justify-content: space-between; color:#5E6278; font-size: 14px; font-weight:500; margin-bottom:8px">
                    <div style="font-family:Arial,Helvetica,sans-serif; font-weight:bold">Contact Person</div>
                    <div style="font-family:Arial,Helvetica,sans-serif">'.$query->emp_contact_person->fullname().'</div>
                </div>
                <div style="display:flex; justify-content: space-between; color:#5E6278; font-size: 14px; font-weight:500; margin-bottom:8px">
                    <div style="font-family:Arial,Helvetica,sans-serif; font-weight:bold">Destination</div>
                    <div style="font-family:Arial,Helvetica,sans-serif">'.$query->destination.'</div>
                </div>
                <div style="justify-content: space-between; color:#5E6278; font-size: 14px; font-weight:500; margin-bottom:8px">
                    <div style="font-family:Arial,Helvetica,sans-serif; font-weight:bold">Purpose :</div>
                    <div style="font-family:Arial,Helvetica,sans-serif">'.$query->purpose.'</div>
                </div>
                <div class="separator separator-dashed" style="margin:15px 0"></div>
            </div>
        </div>
        ';
        return $html;
    }

}

?>
