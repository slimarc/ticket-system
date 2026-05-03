<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePriorityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:50|unique:priorities',
            'estimated_hours' => 'required|numeric|min:0.5',
            'color'           => 'nullable|string|max:20',
        ];
    }
}