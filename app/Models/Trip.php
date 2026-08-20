<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{   protected $guarded = [];
protected $casts = ['days'=>'array'];
    public function user(){
        return $this->belongsToMany(User::class,'bookings');
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function seats(){
        return $this->hasMany(Seats::class);
    }
}
