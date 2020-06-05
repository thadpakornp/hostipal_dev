<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\User;

class Charts_description extends Model
{
    protected $table = 'charts_description';
    protected $fillable = ['charts_id', 'description', 'add_by_user', 'g_location_lat', 'g_location_long', 'type_charts'];

    public function charts(){
        return $this->belongsTo(Charts::class);
    }

    public function charts_files(){
        return $this->hasMany(Charts_files::class,'charts_desc_id','id');
    }

    public function username()
    {
        return $this->hasOne(User::class, 'id', 'add_by_user');
    }

    public function scopeGetchats($query){
        $dt = Carbon::create(date('Y'), date('m'), date('d'),0);
        return $query->where('created_at','>=',$dt->subDays(10));
    }
}
