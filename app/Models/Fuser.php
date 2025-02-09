<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fuser extends Model
{
    use HasFactory;
    
    protected $table = "fusers";
    
    protected $guarded = [];
}
