<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileAvatarUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'avatar' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.required' => 'Selecciona una imagen para el avatar.',
            'avatar.file' => 'El avatar debe ser un archivo válido.',
            'avatar.image' => 'El avatar debe ser una imagen.',
            'avatar.mimes' => 'El avatar solo puede ser JPG, JPEG, PNG o WEBP.',
            'avatar.max' => 'El avatar no puede superar 2 MB.',
        ];
    }
}