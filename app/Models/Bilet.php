<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bilet extends Model
{
    use HasFactory;

    protected $table = 'bilets';

    protected $guarded = [];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
