<?php

namespace Tests\Mocks;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequestMock extends FormRequest
{
    protected $rules = [];
    protected $data = [];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:10',
            'picture' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0'
        ];
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function authorize()
    {
        return true;
    }
}