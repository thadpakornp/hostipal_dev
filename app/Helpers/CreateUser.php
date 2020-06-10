<?php


namespace App\Helpers;


use App\Classes\Resize;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CreateUser
{
    public static function created(array $data)
    {
        $num_user = User::where('type','Owner')->count();

        $user_insert['prefix_id'] = $data['prefix_id'];
        $user_insert['name'] = $data['name'];
        $user_insert['surname'] = $data['surname'];
        $user_insert['phone'] = $data['phone'];
        $user_insert['office_id'] = $data['office_id'];
        $user_insert['email'] = $data['email'];
        $user_insert['password'] = Hash::make($data['password']);
        $user_insert['register_at'] = Carbon::now();

        if($num_user == 0) {
            $user_insert['type'] = 'Owner';
            $user_insert['status'] = 'Active';
        } else {
            $user_insert['type'] = 'User';
            $user_insert['status'] = 'Disabled';
        }
        $user = User::create($user_insert);

        if($user) {
            $profile = time().'.'.$data['file-input']->getClientOriginalExtension();
            $data['file-input']->move(public_path('assets/img/profiles'), $profile);
            Resize::Profile($profile);
            $user_update = User::find($user->id);
            $user_update->profile = $profile;
            $user_update->save();
        }

        return $user;
    }
}
