<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Feedback\UserFeedbackRequest;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function sendUserFeedback(UserFeedbackRequest $request){
        $request->validated();

        return response()->json(["data"=>$request->validated()]);
    }
}
