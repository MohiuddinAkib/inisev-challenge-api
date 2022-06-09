<?php

namespace App\Console\Commands;

use Notification;
use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Console\Command;
use App\Notifications\NewPostPublishedNotification;

class SendNewPostEmailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:new-post-notification {post : id of the post for which to send notification}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail notification to subscribers of a particular website for a new post';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $postId = $this->argument("post");
        $post = Post::findOrFail($postId);
        $emails = $post
            ->website
            ->subscribers
            ->filter(fn (Subscriber $eachSubscriber) => $eachSubscriber->email !== $post->op_email)
            ->map(fn (Subscriber $eachSubscriber) => $eachSubscriber->email);

        $this->withProgressBar($emails, function ($email) use ($post) {
            Notification::route("mail", $email)
                ->notify(new NewPostPublishedNotification($post));
        });
        $this->newLine();
        $this->line("All subscribers notified");
        $this->newLine();

        return 0;
    }
}
