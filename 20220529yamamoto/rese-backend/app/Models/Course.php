<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }
}
