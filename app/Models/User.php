<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prefix_id', 'name', 'email', 'password', 'surname', 'phone', 'profile', 'type', 'status', 'register_at', 'office_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function prefix()
    {
        return $this->hasOne(PrefixModel::class, 'code', 'prefix_id');
    }

    public function scopeGets($query)
    {
        if (Auth::user()->type == 'Owner') {
            return $query->paginate(10);
        } else {
            return $query->where(function ($q) {
                $q->where('office_id', Auth::user()->office_id)->orWhereNull('office_id');
            })->where('type', '<>', 'Owner')->get();
        }
    }

    public function scopeGetAll($query)
    {
        if (Auth::user()->type == 'Owner') {
            return $query->get();
        } else {
            return $query->where('office_id', Auth::user()->office_id)->where('type', '<>', 'Owner')->get();
        }
    }

    public function scopeOwner($query)
    {
        if (Auth::user()->type != 'Owner') {
            return $query->where('type', '<>', 'Owner')->where('office_id', Auth::user()->office_id);
        } else {
            return null;
        }
    }

    public function office()
    {
        return $this->hasOne(OfficeModel::class, 'id', 'office_id');
    }
}
