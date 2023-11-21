<?php

namespace App\Http\Requests\KategoriAsset;

use Illuminate\Foundation\Http\FormRequest;

class KategoriAssetUpdateRequest extends FormRequest
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
            'id_group_kategori_asset' => 'required|uuid|exists:group_kategori_assets,id',
            'kode_kategori' => 'required|string|max:255|unique:kategori_assets,kode_kategori,' . $this->id . ',id,deleted_at,NULL',
            'nama_kategori' => 'required|string|max:255',
            'umur_asset' => 'required|integer|min:0|max:25',
        ];
    }
}
