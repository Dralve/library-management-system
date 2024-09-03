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
            'author' => $this->author,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'borrow_records' => $this->whenLoaded('borrowRecords', function () {
                return $this->borrowRecords->map(function ($borrowRecord) {
                    return [
                        'user' => $borrowRecord->user ? [
                            'id' => $borrowRecord->user->id,
                            'name' => $borrowRecord->user->name,
                            'email' => $borrowRecord->user->email,
                        ] : null,
                        'borrowed_at' => $borrowRecord->created_at,
                        'due_date' => $borrowRecord->due_date,
                    ];
                });
            }),
        ];
    }
}
