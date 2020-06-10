<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\FormatThai;

/**
 * @property mixed date_thai
 * @property mixed date_value
 * @property mixed id
 */

class ChartsMonth extends JsonResource
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
            'date_thai' => FormatThai::DateThaiChartMouth($this->date_thai),
            'date_value' => $this->date_value,
        ];
    }
}
