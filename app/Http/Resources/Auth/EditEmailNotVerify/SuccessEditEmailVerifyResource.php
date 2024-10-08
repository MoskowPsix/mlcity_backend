<?php

namespace App\Http\Resources\Auth\EditEmailNotVerify;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessEditEmailVerifyResource extends JsonResource
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
            'status' => 'success',
            'message' => __('messages.edit_email_not_verify.success'),
            'verification_url' => new UserResource($this->resource),
        ];
    }
}
