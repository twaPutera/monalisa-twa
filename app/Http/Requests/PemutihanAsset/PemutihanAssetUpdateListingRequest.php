<?php

namespace App\Http\Requests\PemutihanAsset;

use Illuminate\Foundation\Http\FormRequest;

class PemutihanAssetUpdateListingRequest extends FormRequest
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
            'id_checkbox.*' => 'required',
            'id_checkbox' => 'required|min:1',
        ];
    }
}
