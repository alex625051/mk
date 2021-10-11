<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActressRequest extends FormRequest
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
            'age'=>'required',
            'country'=>'nullable',
            'filmsCount'=>'required',
            'alive'=>'nullable',
            'films[]'=>'nullable',
            'films.*'=>'required|exists:films,id',
            'file'=>'file|nullable'

            //
        ];
    }
    public function prepareData() {
        return $this->validated();
    }
}
