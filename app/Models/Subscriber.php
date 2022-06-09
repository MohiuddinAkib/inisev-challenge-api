<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email'];

    /**
     * The subscriptions that belong to the subscriber.
     */
    public function subscriptions()
    {
        return $this->belongsToMany(Website::class)->using(Subscription::class);
    }
}
