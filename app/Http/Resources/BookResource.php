<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'trailer' => $this->trailer,
            'publication_year' => $this->publication_year,
            'quantity' => $this->when('>', $this->quantity),
            'author' => $this->author,
            'publisher' => $this->publisher,
            'shell_code' => $this->shell_code
        ];
    }
}
