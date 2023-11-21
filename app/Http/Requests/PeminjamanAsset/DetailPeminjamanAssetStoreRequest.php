<?php

namespace App\Http\Requests\PeminjamanAsset;

use Illuminate\Foundation\Http\FormRequest;

class DetailPeminjamanAssetStoreRequest extends FormRequest
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
            'id_peminjaman_asset' => 'required|uuid|exists:peminjaman_assets,id',
            'id_asset' => 'required|array',
            'id_asset.*' => 'required|uuid|exists:asset_data,id',
        ];
    }
}
