<?php

namespace Workbench\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'name' => ['required_without:email', 'string', 'max:255'],
            'email' => 'required_without:name|email|max:255',
            'password' => 'string|min:8|confirmed',
            'age' => ['nullable', 'integer', 'between:18,99'],
        ];
    }
}
