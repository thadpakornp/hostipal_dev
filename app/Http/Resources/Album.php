<?php

namespace App\Http\Resources;

use App\Models\Charts;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $idcard
 */

class Album extends JsonResource
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
            'id' => $this->id,
            'album' => asset('assets/img/temnails/' . $this->files),
        ];
    }
}
