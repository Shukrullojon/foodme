<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $guarded = [];

    public function from()
    {
        return $this->belongsTo(User::class,'from_id','chat_id');
    }
}
