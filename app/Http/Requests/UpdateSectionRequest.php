<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $bagianId = $this->route('bagian')->id;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('sections')->ignore($bagianId),
            ],
            'status' => 'required|in:aktif,nonaktif',
        ];
    }
}
