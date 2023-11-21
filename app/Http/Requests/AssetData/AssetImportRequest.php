<?php

namespace App\Http\Requests\AssetData;

use Illuminate\Foundation\Http\FormRequest;

class AssetImportRequest extends FormRequest
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
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ];
    }
}
