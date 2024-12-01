<?php
namespace App\Services;

use App\Models\HrisRole;
use App\Models\HrisRoleAccess;
use App\Models\HrisSystemFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WebRoute
{
    public function getWebRoutes($role_id)
    {
        try {
            $role = HrisRole::find($role_id);
            $role_name = strtolower($role->name);

            return Cache::rememberForever(''.$role_name.'_routes', function () use($role) {
                $file_id = HrisRoleAccess::where('role_id',$role->id)->pluck('file_id');
                return HrisSystemFile::with([
                    'file_layer'=>function($q){
                        $q->where('status',1);
                    }
                ])->whereIn('id',$file_id)->where('status',1)->get();
            });
        } catch (\Exception $e) {

            Log::error('Error retrieving '.$role->name.' routes: ' . $e->getMessage());
            return array();
        }
    }
}

?>
