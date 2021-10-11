<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone'=>'required',
        ];
    }


    public function prepareData() {
        $data =  $this->validated();
        $data['phone'] = Str::substr(preg_replace("/[^0-9]/", '', $data['phone']), 1);
        return $data;
    }
}
