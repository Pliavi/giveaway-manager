<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giveaway extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "link",
        "descrioption",
        "finish_date",
    ];

    protected $casts = [
        'finish_date' => 'datetime',
        'is_notified' => 'boolean',
        'is_finished' => 'boolean',
    ];

    public function followingAccounts()
    {
        return $this
            ->belongsToMany("App\Models\FollowingAccount")
            ->using("\App\Models\FollowingAccountGiveaway");
    }

    public function user()
    {
        return $this->belongsTo("App\Models\User");
    }
}
