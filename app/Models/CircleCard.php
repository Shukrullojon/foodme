<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CircleCard extends Model
{
    use HasFactory;

    protected $table = 'circle_card';

    protected $guarded = [];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function circle()
    {
        return $this->belongsTo(Circle::class);
    }
}
