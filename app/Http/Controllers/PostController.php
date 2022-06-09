<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\Subscriber;
use App\Constants\FeedbackMessage;
use App\Http\Requests\StorePostRequest;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request, Website $website)
    {
        $data = $request->validated();

        $subscriber = Subscriber::whereEmail($data["op_email"])->first();

        $this->authorize("create-post", [$subscriber, $website]);

        try {
            $website->posts()->create($data);

            return response()->json([
                "status" => Response::HTTP_OK,
                "message" => FeedbackMessage::POST_CREATE_SUCCESS
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => app()->environment("local") ? $th->getMessage() : Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
