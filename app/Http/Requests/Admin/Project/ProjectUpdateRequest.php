<?php

namespace App\Http\Requests\Admin\Project;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * @property Project $project
 */
class ProjectUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:projects,name,' . $this->project->id],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image'],
            'attributes' => ['nullable', 'array'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['required', 'integer', 'exists:categories,id'],
            'galleries' => ['nullable', 'array'],
            'galleries.*' => ['required', 'image', 'max:2048'],
            'old_galleries' => ['nullable', 'array'],
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }
}
