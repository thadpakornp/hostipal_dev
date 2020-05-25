<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\PrefixModel;
/**
 * @property mixed prefix_id
 * @property mixed name
 * @property mixed surname
 * @property mixed profile
 */

class ChartsUserResource extends JsonResource
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
            'prefix' => PrefixModel::where('code',$this->prefix_id)->first()->name ?? '',
            'name' => $this->name,
            'surname' => $this->surname,
            'profile' => asset('assets/img/profiles/' . $this->profile),
        ];
    }
}
