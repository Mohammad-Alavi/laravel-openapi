<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'github_repo_url' => ['sometimes', 'required', 'url', 'regex:/^https:\/\/github\.com\/.+\/.+$/'],
            'github_branch' => ['nullable', 'string', 'max:255'],
        ];
    }
}
