<?php

namespace App\Http\Controllers\AdminController\Settings\LeaveSettings;

use App\Http\Controllers\Controller;
use App\Models\HrisLeaveSetting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AutomaticCredit extends Controller
{
    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);

            $classification_id = array_map(fn($id) => Crypt::decrypt($id), json_decode($rq->classifications, true) ?? []);
            $employment_id = array_map(fn($id) => Crypt::decrypt($id), json_decode($rq->employment, true) ?? []);
            $location_id = array_map(fn($id) => Crypt::decrypt($id), json_decode($rq->location, true) ?? []);

            $query = HrisLeaveSetting::with('leave_type')
            ->when(!empty($classification_id), fn($q) =>
            $q->whereJsonContains('classification_id', $classification_id)
              ->whereRaw('JSON_LENGTH(classification_id) = ?', [count($classification_id)])
            )
            ->when(!empty($employment_id), fn($q) =>
                $q->whereJsonContains('employment_id', $employment_id)
                ->whereRaw('JSON_LENGTH(employment_id) = ?', [count($employment_id)])
            )
            ->when(!empty($location_id), fn($q) =>
                $q->whereJsonContains('location_id', $location_id)
                ->whereRaw('JSON_LENGTH(location_id) = ?', [count($location_id)])
            )
            ->where([['id', $id],['is_deleted',null]])
            ->first();

            $payload = [];
            if($query){
                $payload = [
                    'start_credit' =>$query->start_credit,

                    'is_carry_over' =>$query->is_carry_over,
                    'carry_over_month' =>$rq->carry_over_month,
                    'carry_over_day' =>$rq->carry_over_day,

                    'fiscal_year' =>$query->fiscal_year,
                    'assign_type' =>$query->assign_type,

                    'is_reset' =>$query->is_reset,
                    'reset_month' =>$query->reset_month,
                    'reset_day' =>$query->reset_day,

                    'succeeding_year' =>$query->succeeding_year,
                    'increment_milestones' =>$query->increment_milestones,
                ];
            }

            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;

            $attribute = ['id'=>$id];
            $values = [
                'start_credit' =>$rq->start_credit,
                'fiscal_year' =>$rq->fiscal_year,
                'assign_type' =>$rq->fiscal_year,

                'is_carry_over' =>$rq->is_carry_over,
                'carry_over_month' =>$rq->carry_over_month,
                'carry_over_day' =>$rq->carry_over_day,

                'is_reset' =>$rq->is_reset,
                'reset_month' =>$rq->reset_month,
                'reset_day' =>$rq->reset_day,

                'succeeding_year' =>$rq->succeeding_year,
                'increment_milestones' =>$rq->milestones,
            ];

            $json_classification_id = json_decode($rq->classifications, true);
            $classification_ids = $json_classification_id ? array_map(fn($id) => Crypt::decrypt($id), $json_classification_id) : [];

            $json_employment_id = json_decode($rq->employment, true);
            $employment_ids = $json_employment_id ? array_map(fn($id) => Crypt::decrypt($id), $json_employment_id) : [];

            $json_location_ids = json_decode($rq->location, true);
            $location_ids = $json_location_ids ? array_map(fn($id) => Crypt::decrypt($id), $json_location_ids) : [];

            $query = HrisLeaveSetting::updateOrCreate($attribute,$values);
            if (empty($query->location_id) && empty($query->employment_id) && empty($query->classification_id)) {
                $query->update([
                    'location_id' => json_encode($location_ids),
                    'employment_id' => json_encode($employment_ids),
                    'classification_id' => json_encode($classification_ids),
                ]);
            }

            if ($query->wasRecentlyCreated) {
                $query->update([ 'created_by'=>$user_id ]);
                $message = 'Set-up is saved successfully';
            }else{
                $query->update(['updated_by' => $user_id, ]);
                $message = 'Set-up is updated successfully';
            }

            DB::commit();
            return response()->json(['status' => 'success','message'=>$message]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status' => 400, 'message' => $e->getMessage()]);
        }
    }
}
