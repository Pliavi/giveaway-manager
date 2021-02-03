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
        "description",
        "finish_date",
    ];

    protected $casts = [
        'finish_date' => 'datetime:Y-m-d',
        'is_notified' => 'boolean',
        'is_finished' => 'boolean',
    ];

    protected $dates = [
        'finish_date'
    ];

    public function followingAccounts()
    {
        return $this
            ->belongsToMany(FollowingAccount::class)
            ->using(FollowingAccountGiveaway::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
