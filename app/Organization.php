<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Organization extends Model
{
    //
    protected $table = 'organizations';
    protected $fillable = [
        'user_id', 'name','telephone','email','website','status'
    ];
    public function users()
    {
        return $this->belongsToMany('App\User', 'organization_user', 'org_id', 'user_id');
    }
    public function organization_setting()
    {
        return $this->hasOne('App\Setting', 'org_id', 'id');
    }
    public function categories()
    {
        return $this->hasMany('App\Category', 'org_id', 'id');
    }
    public function applications()
    {
        return $this->hasMany('App\Application', 'org_id', 'id');
    }
    /*
    Disables the jobs that are expired by end_date_time
    */
    public static function disableExpiredJobs()
    {
        $organizations = Organization::where('status', 1)->get();
        
        foreach ($organizations as $organization) {
            $organization
              ->categories()
              ->where('status', 1)
              ->whereDate('end_date_time', '<=', Carbon::now())
              ->update(['status'=> 0]);
        }
    }
}
