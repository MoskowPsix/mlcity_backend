<?php

namespace App\Http\Resources\Auth\Logout;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class SuccessLogoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'status' => 'success',
            'message' => __('messages.logout.success'),
        ];
    }
    public function withResponse($request, $response)
    {
        $cookie = Cookie::forget('Bearer_token');
        Session::flush();
        $response->withCookie($cookie);
    }
}
