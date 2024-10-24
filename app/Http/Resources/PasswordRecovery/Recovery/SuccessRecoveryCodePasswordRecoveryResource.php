<?php

namespace App\Http\Resources\PasswordRecovery\Recovery;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessRecoveryCodePasswordRecoveryResource extends JsonResource
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
            "status" => "success",
            "message" => __('messages.password_recovery.recovery.success'),
        ];
    }
}
