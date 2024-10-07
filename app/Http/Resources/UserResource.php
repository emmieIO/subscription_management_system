<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => "$this->firstname $this->lastname",
            'email' => $this->email,
            'token' => isset($this->token) ? $this->token : null,
            'created_at' => $this->created_at
        ];
    }
}
