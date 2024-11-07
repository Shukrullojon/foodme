<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->hasOne(Group::class,'id','group_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
