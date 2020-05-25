<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Charts_files;
use App\Http\Resources\ChartsUserResource as UserResource;
use App\Http\Resources\ChartsFilesResource as ChartsFilesResource;
use App\Models\User;
use App\Helpers\FormatThai;

/**
 * @property mixed description
 * @property mixed add_by_user
 * @property mixed id
 * @property mixed created_at
 */

class ChatsResource extends JsonResource
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
            'description' => $this->description == null ? '' : $this->description,
            'add_by_user' => UserResource::make(User::find($this->add_by_user)),
            'files' => Charts_files::where('charts_desc_id',$this->id)->whereNull('deleted_at')->count() > 0 ? ChartsFilesResource::collection(Charts_files::where('charts_desc_id',$this->id)->whereNull('deleted_at')->get()) : null,
            'created_at' => FormatThai::DateThaiToChat($this->created_at),
        ];
    }
}
