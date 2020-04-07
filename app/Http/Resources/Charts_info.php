<?php

namespace App\Http\Resources;

use App\Models\Charts;
use App\Models\PrefixModel;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed hn
 * @property mixed prefix_id
 * @property mixed name
 * @property mixed surname
 * @property mixed address
 * @property mixed profile
 * @property mixed status
 * @property mixed phone
 */
class Charts_info extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'hn' => $this->hn,
            'prefix' => PrefixModel::where('code',$this->prefix_id)->first()->name ?? '',
            'name' => $this->name,
            'surname' => $this->surname,
            'address' => $this->address,
            'phone' => $this->phone,
            'profile' => asset('assets/img/profiles/' . $this->profile),
            'status' => $this->status,
        ];
    }
}
