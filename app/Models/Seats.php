<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seats extends Model
{
    protected $fillable = ['trip_id', 'user_id', 'state','number_seat'];
}
