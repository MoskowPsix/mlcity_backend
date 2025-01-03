<?php

namespace App\Http\Resources\Auth\VerifyEmailForCode;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessVerifyEmailForCodeResource extends JsonResource
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
            'status'    => 'success',
            'message'   => __('messages.verify.email.success'),
            'user'      => new UserResource($this->resource),
        ];
    }
}
