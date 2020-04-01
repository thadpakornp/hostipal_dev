<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed address
 * @property mixed district
 * @property mixed country
 * @property mixed province
 * @property mixed code
 * @property mixed g_location_lat
 * @property mixed g_location_long
 */
class Office extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => ($this->id) ?? null,
            'name' => ($this->name) ?? null,
            'address' => ($this->address) ?? null,
            'district' => ($this->district) ?? null,
            'country' => ($this->country) ?? null,
            'province' => ($this->province) ?? null,
            'zip' => ($this->code) ?? null,
            'lat' => ($this->g_location_lat) ?? null,
            'long' => ($this->g_location_long) ?? null

        ];
    }
}
