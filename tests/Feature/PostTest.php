<?php

namespace Tests\Feature;

use Notification;
use Tests\TestCase;
use App\Models\Website;
use App\Models\Subscriber;
use App\Constants\FeedbackMessage;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\NewPostPublishedNotification;

class PostTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function user_can_subscribe_to_a_particular_website()
    {
        // input will contain user email & endpoint will have website id
        // if user is already subscribed then just return success response
        $website = Website::factory()->create();
        $email = $this->faker->unique()->email();
        $endpoint = route("subscriptions.store", $website->getKey());

        $response = $this->postJson($endpoint, []);
        $response->assertJsonValidationErrorFor("email");

        $response = $this->postJson($endpoint, compact("email"));
        $response->assertJsonMissingValidationErrors("email");

        $response->assertStatus(200);

        $response->assertJson([
            "status" => Response::HTTP_OK,
            "message" => FeedbackMessage::SUBSCRIPTION_SUCCESS
        ]);
    }

    /** @test */
    public function user_must_subscribe_to_website_to_create_post()
    {
        // input will contain user email, website id, post title & post description
        // after creating the post all the subscriber excluding the op will get email notification
        $website = Website::factory()->create();
        $subscriber = Subscriber::factory()->create();
        $endpoint = route("posts.store", $website->getKey());

        $response = $this->postJson($endpoint, [
            "op_email" => $subscriber->email,
            "title" => $this->faker->realText(50),
            "description" => $this->faker->paragraph(),
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function user_can_create_post_on_a_particular_website()
    {
        Notification::fake();

        // input will contain user email, website id, post title & post description
        // after creating the post all the subscriber excluding the op will get email notification
        $website = Website::factory()->create();
        $subscriber = Subscriber::factory()->create();
        $subscriber2 = Subscriber::factory()->create();
        $website->subscribers()->attach($subscriber->getKey());
        $website->subscribers()->attach($subscriber2->getKey());

        $endpoint = route("posts.store", $website->getKey());

        $response = $this->postJson($endpoint, []);
        $response->assertJsonValidationErrors(["title", "description", "op_email"]);

        $response = $this->postJson($endpoint, [
            "op_email" => $this->faker()->email(),
            "title" => $this->faker->realText(50),
            "description" => $this->faker->paragraph(),
        ]);

        $response->assertJsonValidationErrors("op_email");

        $response = $this->postJson($endpoint, [
            "op_email" => $subscriber->email,
            "title" => $this->faker->realText(50),
            "description" => $this->faker->paragraph(),
        ]);

        Notification::assertCount(1);
        Notification::assertSentOnDemand(NewPostPublishedNotification::class, function ($notification, $channels, $notifiable) use ($subscriber2) {
            return $notifiable->routes['mail'] === $subscriber2->email;
        });

        $response->assertStatus(200);
    }
}
