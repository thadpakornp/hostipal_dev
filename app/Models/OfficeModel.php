<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class OfficeModel extends Model
{
    protected $table = 'offices';
    protected $fillable = ['name','phone','address','district','country','province','code','g_location_long', 'g_location_lat'];

    public function scopeManage($query)
    {
        if(Auth::user()->type == 'Owner'){
            return $query->whereNull('deleted_at');
        }

        if(Auth::user()->type == 'Admin'){
            return $query->whereNull('deleted_at')->where('id',Auth::user()->office_id);
        }
    }

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function scopeType($query){
        if(Auth::user()->type == 'Owner'){
            return null;
        } else {
            return $query->where('id',Auth::user()->office_id);
        }
    }
}
