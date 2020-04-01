<?php

namespace App\Http\Resources;

use App\Models\Charts;
use App\Models\PrefixModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $idcard
 */

class Chart extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => Charts::where('idcard',$this->idcard)->orderBy('id','desc')->first()->id ?? '',
            'hn' => Charts::where('idcard',$this->idcard)->orderBy('id','desc')->first()->hn ?? '',
            'prefix' => PrefixModel::where('code',Charts::where('idcard',$this->idcard)->orderBy('id','desc')->first()->prefix_id)->first()->name ?? '',
            'name' => Charts::where('idcard',$this->idcard)->orderBy('id','desc')->first()->name ?? '',
            'surname' => Charts::where('idcard',$this->idcard)->orderBy('id','desc')->first()->surname ?? '',
            'address' => Charts::where('idcard',$this->idcard)->orderBy('id','desc')->first()->address ?? '',
            'profile' => asset('assets/img/profiles/' . Charts::where('idcard',$this->idcard)->orderBy('id','desc')->first()->profile ?? '')
        ];
    }
}
