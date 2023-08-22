<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Sight;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Comment;

class CommentController extends Controller
{

    public function create(CommentRequest $request): \Illuminate\Http\JsonResponse
    {
        if ($request->has('eventID')){
            $event =  Event::where('id', $request->eventID)->firstOrFail();
            $event->comments()->create([
                'text' => $request->text,
                'user_id' => auth()->user()->id,
            ]);
        }

        if ($request->has('sightID')){
            $sight =  Sight::where('id', $request->sightID)->firstOrFail();
            $sight->comments()->create([
                'text' => $request->text,
                'user_id' => auth()->user()->id,
            ]);
        }

        return response()->json(['status' => 'success',], 200);
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        Comment::where('id', $id)->delete();
        return response()->json(['status' => 'success',], 200);
    }
}
