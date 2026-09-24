<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRfidTagRequest;
use App\Models\User;
use App\Services\MototrackDevice\RfidTagNumberAssignmentService;

class RfidTagController extends Controller
{
    public function __construct(
        private readonly RfidTagNumberAssignmentService $rfidTags,
    ) {}

    public function update(UpdateRfidTagRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', auth('api')->id())->firstOrFail();

        $rfidTagNumber = $this->rfidTags->prepareForUser(
            $user->id,
            $request->input('rfidTagNumber')
        );

        $user->update(['rfid_tag_number' => $rfidTagNumber]);

        return response()->json([
            'status' => 'success',
            'message' => $rfidTagNumber === null
                ? 'Номер датчика успешно отвязан.'
                : 'Номер датчика успешно сохранён.',
            'user' => $user->fresh(),
        ], 200);
    }

    public function detach(): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', auth('api')->id())->firstOrFail();

        $this->rfidTags->clearForUser($user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Номер датчика успешно отвязан.',
            'user' => $user->fresh(),
        ], 200);
    }
}
