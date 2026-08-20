<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use tidy;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ([
            'name'=>$this->name,
            'address'=>$this->address,
            'email'=>$this->email,
            'state'=>$this->state,
            'timestamps'=>$this->timestamps,
            'id'=>$this->id
        ]);
    }
}
