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

    public function challengesAsPlayer1()
    {
        return $this->hasMany(Challenge::class, 'player1_user_id');
    }

    public function challengesAsPlayer2()
    {
        return $this->hasMany(Challenge::class, 'player2_user_id');
    }

    public function allChallenges()
    {
        return $this->ChallengesAsPlayer1()->with(['player1', 'player2'])->get()
        ->merge($this->ChallengesAsPlayer2()->with(['player1', 'player2'])->get());
    }

}