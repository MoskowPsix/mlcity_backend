<?php

namespace App\Http\Resources\Organization\OrganizationTransferUser;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessOrganizationTransferUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => 'success',
            'message' => __('organization.transfer_user.success'),
        ];
    }
}
