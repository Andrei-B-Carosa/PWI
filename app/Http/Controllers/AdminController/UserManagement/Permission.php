<?php

namespace App\Http\Controllers\AdminController\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\HrisSystemFile;
use App\Services\Reusable\DTServerSide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class Permission extends Controller
{
    public function dt(Request $rq)
    {
        $data = HrisSystemFile::with(['role_access'])
        ->where([['is_deleted',null],['status',1]])->orderBy('id', 'ASC')->get();

        $data->transform(function ($item, $key) {
            $last_updated_by = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
            }

            $assigned_to = [];
            if($item->role_access){
                foreach($item->role_access as $role_access){
                    $assigned_to[]= [
                        'name'=>$role_access->role->name
                    ];
                }
            }

            $item->count = $key + 1;
            $item->assigned_to = $assigned_to;
            $item->last_updated_by = $last_updated_by;
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
}
