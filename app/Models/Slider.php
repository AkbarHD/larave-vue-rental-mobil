<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Slider extends Model
{
    protected $guarded = [];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/sliders/' . $image),
        );
    }
}
