<?php

namespace App\Http\Resources\PasswordRecovery\Recovery;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorExpraidCodePasswordRecoveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "status" => "error",
            "message" => __('messages.password_recovery.recovery.error_code')
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(403);
    }
}
