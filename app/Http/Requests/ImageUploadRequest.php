<?php

namespace App\Http\Requests;

class ImageUploadRequest extends KuviaRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'image' => [
                'required',
            ]
        ];
    }
}
