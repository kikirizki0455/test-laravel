<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'author'        => $this->author,
            'isbn'          => $this->isbn,
            'published_year'=> $this->published_year,
            'stock'         => $this->stock,
        ];
    }
}
