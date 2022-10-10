<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'id'                => (int) $this->id,
            'isbn'              => $this->isbn,
            'title'             => $this->title,
            'description'       => $this->description,
            'published_year'    => (int) $this->published_year,
            'authors'           => $this->authors->pick('id', 'name', 'surname'),
            'review'            => [
                'avg'               => (int) round($this->reviews->count()),
                'count'             => (int) $this->reviews->average('review'),
            ]
        ];
    }
}
