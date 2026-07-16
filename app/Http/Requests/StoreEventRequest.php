<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:45',
            'description' => 'required|string|max:2000',
            'location'    => 'nullable|string|max:45',
            'date_time'   => 'required|date|after:now',
            'max_players' => 'required|integer|min:2|max:100',
            'entry_fee'   => 'required|numeric|min:0|max:999.99',
            'game_id'     => 'required|exists:games,id',
        ];
    }
}
