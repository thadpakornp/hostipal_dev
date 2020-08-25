<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed files
 * @property mixed type_files
 */
class ChartsFilesResource extends JsonResource
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
            'id' => $this->id,
            'files' => asset('assets/img/temnails/' . $this->files),
            'type_files' => $this->type_files,
        ];
    }
}
