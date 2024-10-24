<?php

namespace App\Http\Resources\PasswordRecovery\Send;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorNotMailSendPasswordRecoveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status' => 'error',
            'message' => __('messages.password_recovery.send.error_not_mail_send'),
        ];
    }
    public function withResponse($request, $response)
    {
        $response->setStatusCode(405);
    }
}
