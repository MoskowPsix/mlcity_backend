<?php

namespace App\Http\Resources\Auth\ResetPasswordForAdmin;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResetPasswordForAdminResource extends JsonResource
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
            'status' => 'error',
            'messages' => __('messages.user.reset_password_for_admin.error')
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(500);
    }
}
