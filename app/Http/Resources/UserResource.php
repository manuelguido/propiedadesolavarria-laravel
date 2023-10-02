<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * Transform the resource into an JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function collectionToJson($collection)
    {
        return $this->collection($collection)->response()->getData(true);
    }
}
