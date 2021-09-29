<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilmRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required',
            'year'=>'nullable',
            'country'=>'nullable',
            'colored'=>'nullable',
            'actresses[]'=>'nullable',
            'actresses.*'=>'required|exists:actresses,id'

            //
        ];
    }
    public function prepareData() {
        return $this->validated();
    }
}
