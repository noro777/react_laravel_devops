<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            "author" => $this->author,
            "text" => $this->text,
            "image" => url($s3->url('images/books/' . $this->image)),
            "file" => $this->file,
            "comments" => new CommentResourceCollection($this->comments),
            "is_now" => $this->created_at >= now()->subDays(3)
        ];
    }
}
