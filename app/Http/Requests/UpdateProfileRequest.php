<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => 'sometimes|string|max:255',
            'bio'              => 'sometimes|nullable|string',
            'country'          => 'sometimes|nullable|string|max:10',
            'favorite_game_id' => 'sometimes|nullable|exists:games,id',
        ];
    }
}
