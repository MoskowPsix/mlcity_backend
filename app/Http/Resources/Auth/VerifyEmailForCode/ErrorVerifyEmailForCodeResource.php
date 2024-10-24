<?php

namespace App\Http\Resources\Auth\VerifyEmailForCode;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorVerifyEmailForCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'status'    => 'error',
            'message'   => __('messages.verify.email.error'),
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(500);
    }
}
