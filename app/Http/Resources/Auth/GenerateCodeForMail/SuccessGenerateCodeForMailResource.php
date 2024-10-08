<?php

namespace App\Http\Resources\Auth\GenerateCodeForMail;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGenerateCodeForMailResource extends JsonResource
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
            'message' => __('messages.verify.verification_code_sent.success'),
        ];
    }
}
