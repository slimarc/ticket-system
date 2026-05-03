<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'sector_id'   => 'required|exists:sectors,id',
            'priority_id' => 'required|exists:priorities,id',
            'requester'   => 'nullable|string|max:100',
        ];
    }
}