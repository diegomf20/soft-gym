<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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
                // 'dni'=>'unique:cliente,dni',
                'nombres'=>'required',
                'ape_paterno'=>'required',
                // 'ape_materno'=>'required',
                // 'telefono'=>'required',
                // 'email'=>'required',
        ];
    }
}
