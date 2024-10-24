<?php

namespace App\Http\Resources\Auth\Register;

use App\Contracts\Services\Auth\AuthService;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessRegisterResource extends JsonResource
{
    private string $token;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $authService = new AuthService();
        $this->token = $authService->getAccessToken($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'status'        => 'success',
            'message'       => __('messages.register.success'),
            'access_token'  => $this->token,
            'token_type'    => 'Bearer',
            'user'          => new UserResource($this->resource)
        ];
    }
    public function withResponse($request, $response)
    {
        $cookie = cookie()->forever('Bearer_token', $this->token);
        $response->withCookie($cookie);
    }
}
