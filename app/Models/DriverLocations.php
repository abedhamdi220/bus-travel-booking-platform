<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverLocations extends Model
{   //////صاحب الموقع
    public function driver(){
      return $this->belongsTo(Driver::class);
    }
}
