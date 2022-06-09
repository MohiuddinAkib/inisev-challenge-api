<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\Subscriber;
use App\Constants\FeedbackMessage;
use App\Http\Requests\StoreSubscriptionRequest;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreSubscriptionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubscriptionRequest $request, Website $website)
    {
        $validated = $request->validated();

        $subscriber = Subscriber::firstOrCreate([
            "email" => $validated["email"]
        ]);

        try {
            $website->subscribers()->attach($subscriber->getKey());

            return response()->json([
                "status" => Response::HTTP_OK,
                "message" => FeedbackMessage::SUBSCRIPTION_SUCCESS
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => app()->environment("local") ? $th->getMessage() : Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
