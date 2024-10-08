<?php

namespace App\Http\Resources\Auth\ResetEmailForCode;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResetEmailForCodeResource extends JsonResource
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
            'message'   => __('messages.verify.reset_email.success'),
            'user'      => new UserResource($this->resource),
        ];
    }
}
