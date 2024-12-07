<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\Controller;
use App\Models\HrisApprovingOfficer;
use App\Models\HrisGroupApprover;
use App\Models\HrisRoleAccess;
use App\Models\HrisSystemFile;
use App\Models\HrisUserRole;
use App\Services\Employee\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function system_file(Request $rq)
    {
        $user_id = Auth::user()->emp_id;
        $user_role = HrisUserRole::where([['role_id',2],['emp_id',$user_id],['is_active',1]])->first();
        if(!$user_role->is_active || !$user_role)
        {
            //throw error
        }

        $query = HrisRoleAccess::with('system_file.file_layer')
        ->where([['is_active',1],['role_id',$user_role->role_id]])->orderBy('file_order')->get();
        if(!$query)
        {
            //throw error
        }

        $result = [];
        $is_approver = HrisGroupApprover::where([['emp_id',Auth::user()->emp_id],['is_active',1]])->exists();
        foreach($query as $data)
        {
            if($data->file_id == 7 && !$is_approver){
                continue;
            }

            $file_layer = [];
            foreach($data->system_file->file_layer as $row)
            {
                $file_layer[]=[
                    'name'=>$row->name,
                    'href'=>$row->href,
                    'icon'=>$row->icon,
                ];
            }

            $result[]=[
                'name'=>$data->system_file->name,
                'href'=>$data->system_file->href,
                'icon'=>$data->system_file->icon,
                'file_layer'=>$file_layer,
            ];
        }
        return view('employee._layout.app',compact('result','is_approver'));
    }

    public function setup_page(Request $rq)
    {
        $role    = 'employee';
        $page = new Page;
        $rq->session()->put($role.'_page',$rq->page);
        $view = $rq->session()->get($role.'_page','home');

        switch($view){

            default :

                $row = HrisSystemFile::with(["file_layer" => function($q) use($view) {
                    $q->where([["status", 1], ["href", $view]]);
                }])
                ->where(function($query) use($view) {
                    $query->where([["status", 1], ["href", $view]])
                    ->orWhereHas("file_layer", function ($q) use($view) {
                        $q->where([["status", 1], ["href", $view]]);
                    });
                })
                ->first();

                if (!$row) { return view("$role.not_found"); }
                $folders = !$row->file_layer->isEmpty()? $row->folder.'.'.$row->file_layer[0]->folder :$row->folder;
                $file    = $row->file_layer[0]->href??$row->href;

                return response([ 'page' => view("$role.$folders.$file")->render() ],200);

            break;
        };
    }
}
