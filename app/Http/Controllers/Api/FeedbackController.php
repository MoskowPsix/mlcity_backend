<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Feedback\UserFeedbackRequest;
use App\Mail\UserFeedback;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    public function sendUserFeedback(UserFeedbackRequest $request)
    {
        try{
            $data = $request->validated();

            Mail::to("support@mlcity.ru")->send(new UserFeedback($data));
            return response()->json([
                'status' => "success",
                "message"=>"mail has been sent",
            ], 200);
        }
        catch (Exception $e){
            return response()->json([
                'status' => "success",
                "message"=>"mail server error"
            ], 400);
        }
    }
}
