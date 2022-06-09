<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Website;
use App\Models\Subscriber;
use App\Models\Subscription;
use App\Constants\FeedbackMessage;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the Subscriber can create models.
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(?User $user, Subscriber $subscriber, Website $website)
    {
        return Subscription::whereSubscriberId($subscriber->getKey())->whereWebsiteId($website->getKey())->exists()
            ? Response::allow()
            : Response::deny(FeedbackMessage::WEBSITE_NOT_SUBSCRIBED);
    }
}
