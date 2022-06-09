<?php

namespace App\Observers;

use Notification;
use App\Models\Post;
use App\Models\Subscriber;
use App\Notifications\NewPostPublishedNotification;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function created(Post $post)
    {
        $emails = $post
            ->website
            ->subscribers
            ->filter(fn (Subscriber $eachSubscriber) => $eachSubscriber->email !== $post->op_email)
            ->map(fn (Subscriber $eachSubscriber) => $eachSubscriber->email);

        $emails->each(function ($email) use ($post) {
            Notification::route("mail", $email)
                ->notify(new NewPostPublishedNotification($post));
        });
    }

    /**
     * Handle the Post "updated" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function updated(Post $post)
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function deleted(Post $post)
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function restored(Post $post)
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
        //
    }
}
