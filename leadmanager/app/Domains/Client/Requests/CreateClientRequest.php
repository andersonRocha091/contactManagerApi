<?php

namespace App\Domains\Client\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'name'=>'required|string|max:255',
            'email'=>'required|string|max:255',
            'phone'=> 'nullable|string|max:20',
            'mobile'=> 'nullable|string|max:20',
            'address'=>'nullable|string|max:255',
            'city'=>'nullable|string|max:255',
            'district'=>'nullable|string|max:255',
            'state'=>'nullable|string|max:40',
            'zip'=>'nullable|string|max:9',
            'picture'=>'nullable|string',
            'age' => 'nullable|integer|min:1'
        ];
    }
}