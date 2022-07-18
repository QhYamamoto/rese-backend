<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function user()
    {
        return $this->belongsToMany(Shop::class, 'favorites', 'shop_id', 'user_id');
    }

    public function representative()
    {
        return $this->belongsTo(User::class, 'representative_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
