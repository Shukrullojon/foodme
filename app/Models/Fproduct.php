<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fproduct extends Model
{
    use HasFactory;

    protected $table = "fproducts";

    protected $guarded = [];

    static $statuses = [
        1 => "Active ✅",
        0 => "Arxive ❌",
    ];

    public function category()
    {
        return $this->belongsTo(Fcategory::class, 'category_id', 'id');
    }
}
