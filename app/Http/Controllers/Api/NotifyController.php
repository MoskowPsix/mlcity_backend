<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notify;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class NotifyController extends Controller
{
    public function private(int $id)
    {
        $headers = [
            "Content-Type" => "text/event-stream",
            "Cache-Control" => "no-cache",
            "Connection" => "keep-alive",
            "X-Accel-Buffering" => "no",
        ];

        return response()->stream(function () use($id) {
            while (true) {
                // Fetch the unread notifications for the authenticated user
                $notifications = Notify::where('user_id', $id)
                    ->where('status', false)
                    ->where('private', true)
                    ->orderBy('created_at');
                // If there are notifications, send them to the frontend
                    // Format notifications as JSON and send them via SSE
                echo "data: " . json_encode($notifications->get()) . "\n\n";


                // Flush the output buffer
                ob_flush();
                flush();

                // Sleep for a few seconds before checking again
                sleep(5);
            }
        }, 200, $headers);
    }

    public function public(Request $request)
    {
        $headers = [
            "Content-Type" => "text/event-stream",
            "Cache-Control" => "no-cache",
            "Connection" => "keep-alive",
            "X-Accel-Buffering" => "no",
        ];

        return response()->stream(function () {
            while (true) {
                // Fetch the unread notifications for the authenticated user
                $notifications = Notify::where('status', false)
                    ->where('private', false)
                    ->whereBetween('created_at', [now()->subMinutes(1), now()])
                    ->orderBy('created_at');
                // If there are notifications, send them to the frontend
                // Format notifications as JSON and send them via SSE
                echo "data: " . json_encode($notifications->get()) . "\n\n";

                // Flush the output buffer
                ob_flush();
                flush();

                // Sleep for a few seconds before checking again
                sleep(20);
            }
        }, 200, $headers);
    }

    public function view(int $id)
    {
        $notify = Notify::find($id);
        $notify->status = true;
        $notify->save();

        return response()->json(["status" => "success"]);
    }
}
