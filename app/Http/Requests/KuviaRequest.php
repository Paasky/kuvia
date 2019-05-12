<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class KuviaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    abstract public function rules() : array;

    public function allVerified() : array
    {
        $verifiedParams = [];
        foreach ($this->all() as $key => $value) {
            if(in_array($key, $this->rules(), true)) {
                $verifiedParams[$key] = $value;
            }
        }

        return $verifiedParams;
    }
}
