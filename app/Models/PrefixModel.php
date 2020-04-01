<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrefixModel extends Model
{
    protected $table = 'prefix';

    public function users(){
        return $this->belongsTo(User::class);
    }
}
