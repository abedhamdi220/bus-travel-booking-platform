<?php

namespace App\Http\Resources;

use App\Models\Company;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class documentResoures extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ([
            'owner'=>$this->documentable_type,
            'typeFile'=>$this->typeFile,
            'pathDoc'=>$this->pathDoc,

        ]);
    }
}
