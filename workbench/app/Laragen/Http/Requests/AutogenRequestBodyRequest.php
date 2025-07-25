<?php

namespace Workbench\App\Laragen\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => ['required_with:email', 'string', 'max:255'],
            'email' => 'required_with:name|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'age' => ['nullable', 'integer', 'between:18,99'],
        ];
    }
}
