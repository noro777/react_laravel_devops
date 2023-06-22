<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $s3 = \Storage::disk('s3');
        return [
            "id" => $this->id,
            "name" => $this->name,
            "text" => $this->text,
            "books" => new BookResourceCollection($this->books),
            "comments" => new CommentResourceCollection($this->comments),
            "image" => url($s3->url('/images/authors/' . $this->image))
        ];
    }
}
