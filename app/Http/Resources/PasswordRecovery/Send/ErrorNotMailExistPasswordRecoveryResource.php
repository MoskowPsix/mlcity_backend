<?php

namespace App\Http\Resources\PasswordRecovery\Send;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorNotMailExistPasswordRecoveryResource extends JsonResource
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
            'status'    => 'error',
            'message'   => __('messages.password_recovery.send.error_not_mail_exist'),
        ];;
    }
    public function withResponse($request, $response)
    {
        $response->setStatusCode(404);
    }
}
