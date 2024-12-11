<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'ranking',
        'height',
        'playing_hand',
        'backhand_style',
        'briefing'
    ];
    
    protected $table = 'players';  
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function challengesPlayer1()
    {
        return $this->hasMany(Challenge::class, 'player1_user_id');
    }

    public function challengesPlayer2()
    {
        return $this->hasMany(Challenge::class, 'player2_user_id');
    }

}