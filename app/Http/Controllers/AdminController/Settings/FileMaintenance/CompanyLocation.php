<?php

namespace App\Http\Controllers\AdminController\Settings\FileMaintenance;

use App\Http\Controllers\Controller;
use App\Models\HrisCompanyLocation;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CompanyLocation extends Controller
{
    public function dt(Request $rq)
    {
        $emp_id = Auth::user()->emp_id;
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;

        $data = HrisCompanyLocation::with('company')->where([['is_deleted',null],['is_active','!=',0]])->orderBy('id', 'ASC') ->get();

        $data->transform(function ($item, $key) {
            $last_updated_by = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
            }
            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
            $item->company_name = $item->company->name;

            $item->encrypted_id = Crypt::encrypt($item->id);
            return $item;
        });

        $table = new DTServerSide($rq, $data);
        $table->renderTable();

        return response()->json([
            'draw' => $table->getDraw(),
            'recordsTotal' => $table->getRecordsTotal(),
            'recordsFiltered' =>  $table->getRecordsFiltered(),
            'data' => $table->getRows()
        ]);
    }

    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = HrisCompanyLocation::with('company')->find($id);
            $payload = [
                'name' =>$query->name,
                'company_id' =>$query->company->name,
                'description' =>$query->description,
                'is_active' =>$query->is_active,
            ];
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
                'name' =>ucwords(strtolower($rq->name)),
                'company_id' =>Crypt::decrypt($rq->company_id),
                'description' =>$rq->description,
                'is_active' =>$rq->is_active,
            ];
            $query = HrisCompanyLocation::updateOrCreate($attribute,$values);
            if ($query->wasRecentlyCreated) {
                $query->update([ 'created_by'=>$user_id, ]);
                $message = 'Added successfully';
            }else{
                $query->update([ 'updated_by' => $user_id, ]);
                $message = 'Details is updated';
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message'=>$message]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function validate_request(Request $rq)
    {
        try{
            $excluded_id = isset($rq->id) && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;
            $exists = HrisCompanyLocation::where('name', ucwords(strtolower($rq->name)))
            ->when($excluded_id, function ($q) use ($excluded_id) {
                $q->where('id', '!=', $excluded_id);
            })
            ->where('is_active','!=',0)
            ->exists();
            return response()->json(['valid' => !$exists, 'message' => 'This location is already in use']);
        }catch(Exception $e)
        {
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->id);

            $query = HrisCompanyLocation::find($id);
            $query->is_active = 0;
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Removed successfully',
                'payload' => HrisCompanyLocation::where('is_active',1)->count()
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
