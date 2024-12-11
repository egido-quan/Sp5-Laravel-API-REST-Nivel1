<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'player1_user_id',
        'player1_user_id',
        'score',
    ];
    
    protected $table = 'challenges';  

    public function player1()
    {
        return $this->belongsTo(Player::class, 'player1_user_id');
    }

    public function player2()
    {
        return $this->belongsTo(Player::class, 'player2_user_id');
    }
    
}
