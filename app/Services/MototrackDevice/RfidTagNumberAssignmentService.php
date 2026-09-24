<?php

namespace App\Services\MototrackDevice;

use App\Models\RfidTagMapping;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class RfidTagNumberAssignmentService
{
    /**
     * Нормализует номер датчика и проверяет, что он есть в соответствиях и свободен.
     *
     * @throws ValidationException
     */
    public function prepareForUser(int $userId, mixed $tagNumber): ?string
    {
        $normalizedTagNumber = $this->normalize($tagNumber);

        if ($normalizedTagNumber === null) {
            return null;
        }

        if (! $this->existsInMappings($normalizedTagNumber)) {
            throw ValidationException::withMessages([
                'rfidTagNumber' => ['Такого номера датчика нет в системе. Обратитесь к администратору.'],
            ]);
        }

        if ($this->isTakenByAnotherUser($userId, $normalizedTagNumber)) {
            throw ValidationException::withMessages([
                'rfidTagNumber' => ['Этот номер датчика уже привязан к другому аккаунту.'],
            ]);
        }

        return $normalizedTagNumber;
    }

    public function clearForUser(int $userId): void
    {
        User::query()
            ->where('id', $userId)
            ->update(['rfid_tag_number' => null]);
    }

    public function existsInMappings(string $normalizedTagNumber): bool
    {
        return RfidTagMapping::query()
            ->where('moto_tag_number', $normalizedTagNumber)
            ->exists();
    }

    public function isTakenByAnotherUser(int $userId, string $normalizedTagNumber): bool
    {
        return User::query()
            ->where('id', '<>', $userId)
            ->where('rfid_tag_number', $normalizedTagNumber)
            ->exists();
    }

    public function normalize(mixed $tagNumber): ?string
    {
        if ($tagNumber === null) {
            return null;
        }

        $normalizedTagNumber = trim((string) $tagNumber);

        return $normalizedTagNumber === '' ? null : $normalizedTagNumber;
    }
}
