<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    static $statuses = [
        1 => "❌ NoPay",
        4 => "✅ Payed",
    ];

    protected $table = 'orders';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function package()
    {
        return $this->hasOne(Package::class,'id','package_id');
    }
}
