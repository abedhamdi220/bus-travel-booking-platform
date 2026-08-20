<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ['vehicle_number', 'type_vehicle', 'plate_number', 'state','date_work','company_id'];

    // public function driver()
    // {
    //     return $this->hasOne(Driver::class);
    // }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    ///// تقيمات الرحلة
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
    ////get all document for vehicle
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
