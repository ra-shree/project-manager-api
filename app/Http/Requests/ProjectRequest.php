<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return $this->isMethod('PATCH')? $this->patch() : $this->store();
    }

    protected function store(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['nullable', 'string', 'max:500'],
            'manager_id' => ['integer', 'required', Rule::exists('users', 'id')],
            'status' => ['required', 'string', Rule::in(['Draft', 'In Progress', 'Completed', 'On Hold'])],
        ];
    }

    protected function patch(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(['Draft', 'In Progress', 'Completed', 'On Hold'])],
        ];
    }
}
