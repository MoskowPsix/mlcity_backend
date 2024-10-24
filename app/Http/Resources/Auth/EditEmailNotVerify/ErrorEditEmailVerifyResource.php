<?php

namespace App\Http\Resources\Auth\EditEmailNotVerify;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorEditEmailVerifyResource extends JsonResource
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
            'status' => 'error',
            'message' => __('messages.edit_email_not_verify.error'),
        ];
    }

    public function withResponse($request, $response): void
    {
        $response->setStatusCode(500);
    }
}
