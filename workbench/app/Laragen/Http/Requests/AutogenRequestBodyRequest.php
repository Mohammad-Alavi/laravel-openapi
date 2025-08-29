<?php

namespace Workbench\App\Laragen\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

final class AutogenRequestBodyRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required_without:email', 'max:255'],
            'email' => 'required_without:name|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'age' => ['nullable', 'integer', 'between:18,99'],
            'height' => ['integer'],
            'width' => ['integer', 'nullable'],
            'depth' => 'nullable|integer',
            'avatar' => ['nullable', 'image', 'max:2048'],
            'pass' => [
                'required',
                'confirmed',
                Password::default(),
            ],
            'birth' => ['date', 'nullable'],
            'death' => ['date'],
            'current_password' => [
                // Rule::requiredIf(fn (): bool => !is_null($this->user()->password) && $this->filled('new_password')),
                'current_password:api',
            ],
            'new_password' => [
                Password::default(),
                'required_with:current_password',
            ],
            'new_password_confirmation' => 'required_with:new_password|same:new_password',
        ];
    }
}
