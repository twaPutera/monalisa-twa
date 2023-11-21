<?php

namespace App\Http\Requests\KelasAsset;

use Illuminate\Foundation\Http\FormRequest;

class KelasAssetUpdateRequest extends FormRequest
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
            'no_akun' => 'required|string|max:255|unique:kelas_assets,no_akun,' . $this->id . ',id,deleted_at,NULL',
            'nama_kelas' => 'required|string|max:255',
        ];
    }
}
