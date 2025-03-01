<?php 
namespace App\Domains\Client\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'name'=>'sometimes|string|max:255',
            'email'=>'sometimes|string|max:255',
            'phone'=> 'nullable|string|max:20',
            'address'=>'nullable|string|max:255',
            'city'=>'nullable|string|max:255',
            'state'=>'nullable|string|max:40',
            'zip'=>'nullable|string|max:9',
            'picture'=>'nullable|string',
            'age' => 'sometimes|integer|min:1'
        ];
    }
}