<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Constants\FeedbackMessage;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Website $website)
    {

        $data = $request->validate(
            [
                "title" => ["required", "string"],
                "description" => ["required", "string"],
                "op_email" => ["email", "exists:App\Models\Subscriber,email", "required"]
            ],
            [
                "op_email.exists" => FeedbackMessage::POST_OP_EMAIL_IS_NOT_SUBSCRIBED
            ]
        );

        $subscriber = Subscriber::whereEmail($data["op_email"])->first();

        $response = Gate::inspect("create-post", [$subscriber, $website]);

        if (!$response->allowed()) {
            return response()->json([
                "message" => $response->message()
            ], Response::HTTP_FORBIDDEN);
        }
        try {
            $website->posts()->create($data);

            return response()->json([
                "status" => Response::HTTP_OK,
                "message" => FeedbackMessage::POST_CREATE_SUCCESS
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
