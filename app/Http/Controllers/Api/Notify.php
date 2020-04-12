<?php

namespace App\Http\Controllers\Api;

use App\Events\SendNotifyToWeb;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class Notify extends Controller
{
    public function sentNotifyWeb($id, $title, $content, $action = null)
    {
        event(new SendNotifyToWeb($id, $title, $content, $action));
    }

    public function sentNotifyDevice($title, $body, $device_token, $my_data = null)
    {
        try {
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60 * 20);

            $notificationBuilder = new PayloadNotificationBuilder($title);
            $notificationBuilder->setBody($body)
                ->setIcon('ic_launcher')
                ->setSound('default');


            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(['click_action' => 'FLUTTER_NOTIFICATION_CLICK', 'id' => $my_data, 'status' => 'done']);

            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            $token = $device_token;
            FCM::sendTo($token, $option, $notification, $data);
        } catch (\Exception $exceptione) {
            dd($exceptione->getMessage());
        }
    }
}
