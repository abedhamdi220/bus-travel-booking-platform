<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Driver extends Model
{   use Notifiable;
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    /////////جلب تقييمات السائق
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
    /////get all document for this driver
    public function documents(){
        return $this->morphMany(Document::class,'documentable');
    }
}
