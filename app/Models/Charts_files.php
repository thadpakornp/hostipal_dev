<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charts_files extends Model
{
    protected $table = 'charts_files';
    protected $fillable = ['charts_id', 'charts_desc_id', 'files', 'type_files', 'add_by_user'];

    public function charts_description()
    {
        return $this->belongsToMany(Charts_description::class);
    }

    public function charts()
    {
        return $this->belongsToMany(Charts::class);
    }

    public function users()
    {
        return $this->hasOne(User::class,'id','add_by_user');
    }
}
