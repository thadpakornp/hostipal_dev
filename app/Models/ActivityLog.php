<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_log';
    protected $fillable = ['user_id','domain','url','path','description','client_ip','guard','method','protocol','user_agent','session_id'];
}
