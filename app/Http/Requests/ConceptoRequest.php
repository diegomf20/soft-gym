<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConceptoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'id'=>'required|max:3|min:3|unique:concepto,id',
                'descripcion'=>'required',
        ];
    }
    public function attributes(){
        return [
            "id" => "código"
        ];
    }
}
