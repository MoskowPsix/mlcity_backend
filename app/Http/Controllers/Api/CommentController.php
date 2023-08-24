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
            if($request->has('commentID')) {
                $comment = $event->comments()->create([
                    'text' => $request->text,
                    'user_id' => auth()->user()->id,
                    'comment_id' => $request->commentID
                ]);
            } else {
                $comment = $event->comments()->create([
                    'text' => $request->text,
                    'user_id' => auth()->user()->id,
                ]);
            }
            $comment->user;
            $comment->comments;
            return response()->json(['status' => 'success', 'comment' => $comment], 200);
        } elseif ($request->has('sightID')){
            $sight =  Sight::where('id', $request->sightID)->firstOrFail();
            if($request->has('commentID')) {
                $comment = $sight->comments()->create([
                    'text' => $request->text,
                    'user_id' => auth()->user()->id,
                    'comment_id' => $request->commentID
                ]);
            } else {
                $comment = $sight->comments()->create([
                    'text' => $request->text,
                    'user_id' => auth()->user()->id,
                ]);
            }
            $comment->user;
            $comment->comments;
            return response()->json(['status' => 'success', 'comment' => $comment], 200);
        }
    }

    public function showCommentId($id): \Illuminate\Http\JsonResponse
    {
        $comment = Comment::where('id', $id)->firstOrFail();
        $comment->user;
        $comment->comments;

        return response()->json(['status' => 'success', 'comments' => $comment], 200);
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        Comment::where('id', $id)->delete();
        return response()->json(['status' => 'success',], 200);
    }
}
