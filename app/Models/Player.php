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

    public function user() {
        return $this->belongsTo(User::class);
    }

}