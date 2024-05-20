<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameBilet extends Model
{
    use HasFactory;

    protected $table = 'game_bilet';

    protected $guarded = [];
    
    public function bilet()
    {
        return $this->belongsTo(Bilet::class, 'bilet_id', 'id');
    }

    public function player()
    {
        return $this->hasOne(GamePlayer::class,'id','game_player_id');
    }
}
