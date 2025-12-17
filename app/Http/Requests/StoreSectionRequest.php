<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100|unique:sections,name',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Nama bagian sudah terdaftar.',
        ];
    }
}
