<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRfidTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('rfidTagNumber')) {
            return;
        }

        $normalized = trim((string) $this->input('rfidTagNumber'));

        $this->merge([
            'rfidTagNumber' => $normalized === '' ? null : $normalized,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'rfidTagNumber' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists('rfid_tag_mappings', 'moto_tag_number'),
                Rule::unique('users', 'rfid_tag_number')->ignore(auth('api')->id()),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'rfidTagNumber' => 'номер датчика',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'rfidTagNumber.exists' => 'Такого номера датчика нет в системе. Обратитесь к администратору.',
            'rfidTagNumber.unique' => 'Этот номер датчика уже привязан к другому аккаунту.',
        ];
    }
}
