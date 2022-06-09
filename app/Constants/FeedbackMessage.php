<?php

namespace App\Constants;

interface FeedbackMessage
{
    const POST_CREATE_SUCCESS = "Post created successfully";
    const SUBSCRIPTION_SUCCESS = "Subscription was successful";
    const WEBSITE_NOT_SUBSCRIBED = "You have not subscribed to this website";
    const POST_OP_EMAIL_IS_NOT_SUBSCRIBED = "You have not subscribed to any website yet";
}
