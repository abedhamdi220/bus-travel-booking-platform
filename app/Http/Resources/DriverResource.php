<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ([
            'id'=>$this->id,
            'state'=>$this->state,
            'availability_state'=>$this->availability_state,
            'created_at' => $this->created_at?->format('Y-m-d'),
            'updated_at'=>$this->updated_at?->format('d/m/y'),
            'name'=>$this->user->name,
            'email'=>$this->user->email,
            'role' => $this->user->role,
        ]);
    }
}
