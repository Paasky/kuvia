<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class CollageCreateRequest extends KuviaRequest
{
    public function authorize()
    {
        return (bool) Auth::user();
    }

    public function rules() : array
    {
        return [
            'title' => [
                'required',
                'string',
            ]
        ];
    }
}
