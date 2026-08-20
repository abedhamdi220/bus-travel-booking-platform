<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $fillable = ['phone', 'name', 'address', 'email', 'state','password'];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
    public function getAverageRatingAttribute()
    {
        $AverageRating = round($this->reviews()->avg('rating'), 1) ?: 0;
        $CountReviews = $this->reviews()->count();
        return response()->json([
            "AverageRating $AverageRating",
            "CountReviews  $CountReviews"
        ], 200);
    }
    public function profile(){
        return $this->hasOne(ProfileCompany::class);
    }
    public function myAdsLog()
    {
        return $this->hasMany(AdsLog::class);
    }
    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }
    //////////////////تقييم شركة
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
    public function vehicles(){
        return $this->hasMany(Vehicle::class);
    }
    /////get all document for this company
    public function documents(){
        return $this->morphMany(Document::class,'documentable');
    }
}
