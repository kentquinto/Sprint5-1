<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'game'     => 'sometimes|integer|exists:games,id',
            'status'   => 'sometimes|in:upcoming,ongoing,finished,cancelled',
            'price'    => 'sometimes|in:free,paid',
            'date'     => 'sometimes|date',
            'search'   => 'sometimes|string|max:100',
            'location' => 'sometimes|string|max:100',
        ];
    }
}
