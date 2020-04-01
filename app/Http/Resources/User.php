<?php

namespace App\Http\Resources;

use App\Models\OfficeModel;
use App\Models\PrefixModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed prefix_id
 * @property mixed name
 * @property mixed surname
 * @property mixed email
 * @property mixed phone
 * @property mixed profile
 * @property mixed type
 * @property mixed status
 * @property mixed register_at
 * @property mixed office_id
 */
class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'prefix' => Prefix::make(PrefixModel::where('code',$this->prefix_id)->first()),
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone' => $this->phone,
            'profile' => asset('assets/img/profiles/' . $this->profile),
            'type' => $this->type,
            'status' => $this->status,
            'register_at' => $this->register_at,
            'office' => Office::make(OfficeModel::find($this->office_id))
        ];
    }
}
