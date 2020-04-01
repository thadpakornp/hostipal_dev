<?php


namespace App\Classes;


use Intervention\Image\Facades\Image;

class Resize
{
    public static function Profile($name){
        $thumbnail = Image::make(public_path('assets/img/profiles/'.$name));
        $thumbnail->resize(128, 128)->save(public_path('assets/img/avatars/'.$name));

        chmod(public_path('assets/img/profiles/'.$name), 0777);
        chmod(public_path('assets/img/avatars/'.$name), 0777);
    }

    public static function uploads($name){
        $thumbnail = Image::make(public_path('assets/img/photos/'.$name));
        $thumbnail->resize(720, 480)->save(public_path('assets/img/temnails/'.$name));

        chmod(public_path('assets/img/photos/'.$name), 0777);
        chmod(public_path('assets/img/temnails/'.$name), 0777);
    }
}
