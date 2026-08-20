<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = [];

    ///////معرفة صاحب التقييم
    public function reviewable()
    {
        return $this->morphTo();
    }

}
