<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // تم تغيير pasenger_sex إلى gender وتمت إضافة phone
    protected $fillable = [
        'dateBooking',
        'gender',
        'phone',
        'trip_id',
        'user_id',
        'seat_id'
    ];
}
