<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PasteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'user_id' => $this->user_id,
            'hash' => Str::random(8),
            'title' => $data['title'],
            'content' => $data['content'],
            'language' => $data['language'],
            'expires_at' => $expiresAt,
            'access' => $data['access'],

        ];
    }
}
