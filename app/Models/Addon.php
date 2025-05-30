<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $guarded = [];

    // many to many
    public function rentals()
    {
        return $this->belongsToMany(Rental::class, 'rental_addons')
                    ->withPivot('quantity', 'total_price')
                    ->withTimestamps();
    }
}
