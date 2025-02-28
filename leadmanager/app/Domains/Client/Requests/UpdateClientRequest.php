<?php 
namespace App\Domain\Client\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'name'=>'sometimes|required|string|max:255',
            'email'=>'sometimes|required|string|max:255',
            'phone'=> 'nullable|string|max:20',
            'address'=>'nullable|string|max:255',
            'city'=>'nullable|string|max:255',
            'state'=>'nullable|string|max:40',
            'zip'=>'nullable|string|max:9',
            'picture'=>'nullable|string',
            'age' => 'sometimes|required|integer|min:1'
        ];
    }
}