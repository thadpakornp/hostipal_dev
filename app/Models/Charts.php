<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Charts extends Model
{
    protected $table = 'charts';
    protected $fillable = ['prefix_id', 'name', 'surname', 'idcard', 'sex', 'hbd', 'hn', 'phone', 'address', 'profile', 'status', 'add_by_user', 'g_location_lat', 'g_location_long'];

    public function scopeCharts($query)
    {
        return $query->whereNull('deleted_at')->distinct('idcard')->get(['idcard']);
    }

    public function prefix()
    {
        return $this->hasOne(PrefixModel::class, 'code', 'prefix_id');
    }

    public function username()
    {
        return $this->hasOne(User::class, 'id', 'add_by_user');
    }

    public function charts_description()
    {
        return $this->hasMany(Charts_description::class, 'charts_id', 'id')->whereNull('deleted_at')->orderBy('created_at', 'DESC');
    }

    public function charts_files()
    {
        return $this->hasMany(Charts_files::class, 'charts_id', 'id')->whereNull('deleted_at')->orderBy('created_at', 'DESC');
    }
}
