<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserRequest extends FormRequest
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
        return [
            'first_name' => ['required', 'string', 'max:255', 'min:3'],
            'last_name' => ['required', 'string', 'max:255', 'min:3'],
            'password' => ['required', 'confirmed', 'max:255', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['developer', 'manager'])],
        ] + ($this->isMethod('POST')? $this->store(): $this->update());
    }

    protected function store(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
        ];
    }

    protected function update(): array
    {
        return [
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->route('user_id'))],
        ];
    }
}
