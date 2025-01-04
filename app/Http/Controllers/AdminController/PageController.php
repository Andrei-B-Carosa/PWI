<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\HrisRoleAccess;
use App\Models\HrisSystemFile;
use App\Models\HrisUserRole;
use App\Services\Admin\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function system_file(Request $rq)
    {
        $user_id = Auth::user()->emp_id;
        $user_role = HrisUserRole::where([['role_id',1],['emp_id',$user_id],['is_active',1]])->first();
        if(!$user_role){
            if (session()->has('user_id')) {
                $role = session('user_role');
                $default = session('default');
                return redirect("hris/$role/$default");
            }else{
                return redirect("hris/employee/login");
            }
        }

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
        foreach($query as $data)
        {
            $file_layer = [];
            foreach($data->system_file->file_layer as $row)
            {
                if($row->status == 2){
                    continue;
                }

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

        $role = 'admin';
        return view($role.'._layout.app',compact('result'));
    }

    public function setup_page(Request $rq)
    {
        $page = new Page;
        $role    = 'admin';
        $rq->session()->put($role.'_page',$rq->page);
        $view = $rq->session()->get($role.'_page','dashboard');

        switch($view){

            case 'automatic_credit':
                return response([ 'page' => $page->automatic_credit($rq)], 200);
            break;

            case 'manual_credit':
                return response([ 'page' => $page->manual_credit($rq)], 200);
            break;

            case 'employee_details':
                return response([ 'page' => $page->employee_details($rq)], 200);
            break;

            case 'group_details':
                return response([ 'page' => $page->group_details($rq)], 200);
            break;

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
