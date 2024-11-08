<?php

namespace App\Http\Resources\Auth\Login;

use App\Contracts\Services\Auth\AuthServiceService;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class SuccessLoginResource extends JsonResource
{
    private string $token;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $authService = new AuthServiceService();
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
            'message'       => __('messages.login.success'),
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
