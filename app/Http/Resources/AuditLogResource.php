<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'action' => $this->action,
            'user' => $this->user->email,
            'description' => $this->description,
            'date_created' => date('F d, Y', strtotime($this->created_at)),
            'time_created' => date('h:i:s:A', strtotime($this->created_at)),
        ];
    }
}
