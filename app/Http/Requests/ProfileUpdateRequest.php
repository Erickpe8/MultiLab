<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        foreach (['phone', 'mobile'] as $field) {
            if ($this->input($field) === 'null') {
                $this->merge([$field => null]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $userId = $this->user()?->getKey();

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'first_surname' => ['required', 'string', 'max:255'],
            'second_surname' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', Rule::in(['M', 'F', 'O'])],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($userId),
            ],
            'notify_email' => ['boolean'],
            'notify_in_app' => ['boolean'],
            'digest_frequency' => ['required', Rule::in(['none', 'daily', 'weekly'])],
            'theme' => ['required', Rule::in(['system', 'light', 'dark'])],
            'compact_mode' => ['boolean'],
            'avatar' => ['nullable', 'file', 'image', 'max:2048', 'mimes:jpeg,jpg,png,webp'],
            'phone' => ['nullable', 'string', 'max:64'],
            'mobile' => ['nullable', 'string', 'max:64'],
            'phone_extension' => ['nullable', 'string', 'max:16'],
        ];
    }
}
