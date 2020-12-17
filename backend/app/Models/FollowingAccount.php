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
        return $this->belongsTo(User::class);
    }

    public function socialNetwork(){
        return $this->belongsTo(SocialNetwork::class);
    }

    public function giveaways()
    {
        return $this
            ->belongsToMany(Giveaway::class)
            ->using(FollowingAccountGiveaway::class);
    }
}
