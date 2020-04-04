<?php

namespace App\Http\Resources;

use App\Helpers\FormatThai;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $created_at
 */

class ChartsDate extends JsonResource
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
            'created_at' => FormatThai::DateThaiNoTime($this->created_at),
        ];
    }
}
