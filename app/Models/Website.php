<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Website extends Model
{
    use HasFactory;

    /**
     * The subscribers that belong to the website.
     */
    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class)->using(Subscription::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
