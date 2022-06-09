<?php

namespace App\Http\Requests;

use App\Constants\FeedbackMessage;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "title" => ["required", "string"],
            "description" => ["required", "string"],
            "op_email" => ["email", "exists:App\Models\Subscriber,email"]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            "op_email.exists" => FeedbackMessage::POST_OP_EMAIL_IS_NOT_SUBSCRIBED

        ];
    }
}
