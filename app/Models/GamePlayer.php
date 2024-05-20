<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{
    use HasFactory;

    protected $table = 'game_players';
    
    protected $guarded = [];

    public function bilets()
    {
        return $this->hasMany(GameBilet::class,'game_player_id','id')->where('status',4);
    }
}
