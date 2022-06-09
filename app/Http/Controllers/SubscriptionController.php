<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Constants\FeedbackMessage;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Website $website)
    {
        $validated = $request->validate([
            "email" => ["email", "required"]
        ]);

        $subscriber = Subscriber::firstOrCreate([
            "email" => $validated["email"]
        ]);

        $website->subscribers()->attach($subscriber->getKey());

        return response()->json([
            "status" => Response::HTTP_OK,
            "message" => FeedbackMessage::SUBSCRIPTION_SUCCESS
        ]);
    }
}
