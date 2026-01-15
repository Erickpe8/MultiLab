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

    public function messages(): array
    {
        return [
            'first_name.required' => 'El primer nombre es obligatorio.',
            'first_name.string' => 'El primer nombre debe ser texto.',
            'first_name.max' => 'El primer nombre no puede exceder 255 caracteres.',

            'middle_name.string' => 'El segundo nombre debe ser texto.',
            'middle_name.max' => 'El segundo nombre no puede exceder 255 caracteres.',

            'first_surname.required' => 'El primer apellido es obligatorio.',
            'first_surname.string' => 'El primer apellido debe ser texto.',
            'first_surname.max' => 'El primer apellido no puede exceder 255 caracteres.',

            'second_surname.string' => 'El segundo apellido debe ser texto.',
            'second_surname.max' => 'El segundo apellido no puede exceder 255 caracteres.',

            'gender.in' => 'Selecciona un género válido.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.max' => 'El correo electrónico no puede exceder 255 caracteres.',
            'email.unique' => 'Ya existe un usuario con ese correo electrónico.',

            'notify_email.boolean' => 'La opción de notificar por correo debe ser verdadero o falso.',
            'notify_in_app.boolean' => 'La opción de notificar en la app debe ser verdadero o falso.',

            'digest_frequency.required' => 'La frecuencia de resumen es obligatoria.',
            'digest_frequency.in' => 'Selecciona una frecuencia válida.',

            'theme.required' => 'El tema preferido es obligatorio.',
            'theme.in' => 'Selecciona un tema válido.',

            'compact_mode.boolean' => 'El modo compacto debe ser verdadero o falso.',

            'avatar.file' => 'El avatar debe ser un archivo válido.',
            'avatar.image' => 'El avatar debe ser una imagen.',
            'avatar.max' => 'El avatar no puede superar 2 MB.',
            'avatar.mimes' => 'El avatar solo puede ser JPG, JPEG, PNG o WEBP.',

            'phone.string' => 'El teléfono debe ser texto.',
            'phone.max' => 'El teléfono no puede exceder 64 caracteres.',

            'mobile.string' => 'El celular debe ser texto.',
            'mobile.max' => 'El celular no puede exceder 64 caracteres.',

            'phone_extension.string' => 'La extensión debe ser texto.',
            'phone_extension.max' => 'La extensión no puede exceder 16 caracteres.',
        ];
    }
}