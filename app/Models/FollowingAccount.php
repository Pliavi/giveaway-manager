<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowingAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        "username",
    ];

    protected $casts = [
        "is_unfollowed", "boolean"
    ];

    public function user() {
        return $this->belongsTo("App\Models\User");
    }

    public function socialNetwork(){
        return $this->belongsTo("App\Models\SocialNetwork");
    }
}
